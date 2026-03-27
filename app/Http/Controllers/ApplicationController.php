<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicantData;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications for the Admin.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $applications = Application::with(['user.applicantData'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('course', 'like', "%{$search}%");
            })
            ->latest()->get();

        return view('admin.registry', compact('applications'));
    }

    /**
     * Show the form for creating a new application.
     */
    public function create()
    {
        $user = Auth::user();

        // Prevent admin accounts from accessing the application form
        if ($user->role === 'admin') {
            return redirect()->route('admin.registry')->with('error', 'Admin accounts cannot apply for scholarships.');
        }

        $application = null;
        $applicantData = null;
        $users = [];

        if ($user->role === 'student') {
            $application = Application::where('user_id', $user->id)->first();
            $applicantData = ApplicantData::where('user_id', $user->id)->first();
        } else {
            $users = User::where('role', 'student')->get();
        }

        return view('applications.create', compact('users', 'application', 'applicantData'));

    }

    /**
     * Store a newly created application in storage.
     */
    public function store(Request $request)
    {
        // Prevent admin accounts from submitting applications
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.registry')->with('error', 'Admin accounts cannot submit scholarship applications.');
        }

        // 1. Validation
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'maiden_name' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'sex' => 'required|in:male,female',
            'place_of_birth' => 'required|string|max:255',
            'pob_zip_code' => 'required|string|max:20',
            'permanent_address' => 'required|string|max:500',
            'zip_code' => 'required|string|max:20',
            'mobile_number' => 'required|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'school_name' => 'required|string|max:255',
            'school_id_number' => 'nullable|string|max:255',
            'course' => 'required|string|max:255',
            'school_sector' => 'required|in:public,private',
            'year_level' => 'required|integer|min:1|max:10',
            'school_address' => 'required|string|max:500',
            'citizenship' => 'nullable|string|max:255',
            'total_income' => 'nullable|numeric|min:0',
            'siblings_count' => 'nullable|integer|min:0',
            'applicant_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'indigency_certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'enrollment_proof' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'signature_file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Prepare Data
        $targetUserId = $request->user_id ?? Auth::id();
        $data = $request->except(['applicant_photo', 'indigency_certificate', 'enrollment_proof', 'signature_file', 'year_level']);
        $data['course'] = $request->course;
        $data['year_level'] = $request->year_level;
        $data['has_assistance'] = ($request->has_other_assistance == 'yes') ? 1 : 0;
        $data['user_id'] = $targetUserId;
        $data['citizenship'] = $request->citizenship ?? 'Filipino';
        $data['date_accomplished'] = now()->format('Y-m-d');

        // 3. Handle File Uploads (Stored in public/uploads for easy access)
        if ($request->hasFile('applicant_photo')) {
            $name = time().'_photo.'.$request->applicant_photo->extension();
            $request->applicant_photo->move(public_path('uploads/photos'), $name);
            $data['applicant_photo'] = 'uploads/photos/'.$name;
        }

        if ($request->hasFile('indigency_certificate')) {
            $name = time().'_indigency.'.$request->indigency_certificate->extension();
            $request->indigency_certificate->move(public_path('uploads/docs'), $name);
            $data['indigency_certificate'] = 'uploads/docs/'.$name;
        }

        if ($request->hasFile('enrollment_proof')) {
            $name = time().'_enroll.'.$request->enrollment_proof->extension();
            $request->enrollment_proof->move(public_path('uploads/docs'), $name);
            $data['enrollment_proof'] = 'uploads/docs/'.$name;
        }

        if ($request->hasFile('signature_file')) {
            $name = time().'_sig.'.$request->signature_file->extension();
            $request->signature_file->move(public_path('uploads/sigs'), $name);
            $data['signature_path'] = 'uploads/sigs/'.$name;
        }

        // 4. Save to ApplicantData (The detailed profile)
        ApplicantData::updateOrCreate(['user_id' => $targetUserId], $data);

        // 5. Update Main Application Entry
        Application::updateOrCreate(
            ['user_id' => $targetUserId],
            [
                'course' => $request->course ?? 'N/A',
                'year_level' => $request->year_level ?? 1,
                'status' => 'pending',
            ]
        );

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.registry')->with('success', 'Application recorded successfully.');
        }

        return redirect()->route('applications.success');
    }

    /**
     * Update the CHEDRO/Admin section of the application.
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'application_status' => 'nullable|in:pending,approved,rejected',
        'status' => 'nullable|in:pending,approved,rejected',
        'admin_remarks' => 'nullable|string|max:1000',
        'admin_check_cor' => 'nullable|boolean',
        'admin_check_indigency' => 'nullable|boolean',
        'evaluated_by' => 'nullable|string|max:255',
        'regional_coordinator' => 'nullable|string|max:255',
    ]);

    $application = Application::findOrFail($id);

    $applicantData = ApplicantData::where('user_id', $application->user_id)->firstOrFail();

    $newStatus = $request->application_status ?? $request->status ?? $application->status;

    $applicantData->update([
        'application_status' => $newStatus,
        'admin_remarks' => $request->admin_remarks,
        'evaluated_by' => $request->evaluated_by ?? auth()->user()->name,
        'admin_check_cor' => $request->has('admin_check_cor') ? 1 : 0,
        'admin_check_indigency' => $request->has('admin_check_indigency') ? 1 : 0,
        'regional_coordinator' => $request->regional_coordinator ?? auth()->user()->name,
    ]);

    $application->status = $newStatus;
    $application->save();

    return back()->with('success', 'Application for ' . $applicantData->first_name . ' has been updated.');
}

    /**
     * Display a specific application for Admin review.
     */
    public function show($id)
{
    // Fetch application and related applicant data
    $application = Application::with('user.applicantData')->find($id);

    if (!$application) {
        return redirect()->route('admin.registry')->with('error', 'Application not found for ID ' . $id);
    }

    // Security: Prevent students from seeing other students' applications
    if (Auth::user()->role !== 'admin' && $application->user_id !== Auth::id()) {
        abort(403, 'You are not authorized to view this application.');
    }

    $applicantData = $application->applicantData;

    if (!$applicantData) {
        return redirect()->route('admin.registry')->with('error', 'Applicant data not found for this application.');
    }

    return view('admin.review', compact('application', 'applicantData'));
}

    /**
     * Delete an application.
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);

        // Check if user is admin or owns the application
        if (Auth::user()->role !== 'admin' && $application->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own application.');
        }

        // Students can only delete pending applications
        if (Auth::user()->role === 'student') {
            $applicantData = ApplicantData::where('user_id', Auth::id())->first();
            $currentStatus = $applicantData?->application_status ?? $application->status ?? 'pending';

            if ($currentStatus !== 'pending') {
                return back()->with('error', 'You cannot delete an application that has been ' . $currentStatus . '.');
            }
        }

        $application->delete();

        // Also delete associated applicant data if it exists
        ApplicantData::where('user_id', $application->user_id)->delete();

        return back()->with('success', 'Application deleted.');
    }

    public function success()
    {
        return view('applications.success');
    }

    // --- USER MANAGEMENT METHODS ---

    public function userIndex()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created.');
    }

   public function viewForm()
{
    // Get the application and the detailed data for the logged-in student
    $application = Application::where('user_id', Auth::id())->first();
    $applicantData = ApplicantData::where('user_id', Auth::id())->first();

    if (!$applicantData) {
        return redirect()->route('student.dashboard')->with('error', 'No application data found.');
    }

    return view('applications.view_form', compact('application', 'applicantData'));
}

    public function downloadPdf()
{
    $application = Application::where('user_id', Auth::id())->firstOrFail();
    $applicantData = ApplicantData::where('user_id', Auth::id())->firstOrFail();

    $pdf = Pdf::loadView('applications.view_form_pdf', compact('application', 'applicantData'))
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 150,
        ]);

    return $pdf->download('application_' . $application->id . '.pdf');
}

    public function downloadAdminPdf($id)
{
    $application = Application::with('user.applicantData')->findOrFail($id);
    $applicantData = $application->applicantData;

    $pdf = Pdf::loadView('applications.view_form_pdf', compact('application', 'applicantData'))
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 150,
        ]);

    return $pdf->download('application_' . $application->id . '.pdf');
}

public function update(Request $request, $id)
{
    // 1. Find the application or fail immediately
    $application = Application::findOrFail($id);
    $user = auth()->user();

    // --- CASE 1: ADMIN UPDATING EVALUATION ---
    if ($user->role === 'admin') {
        // Find the applicant data instead of application
        $applicantData = ApplicantData::where('user_id', $application->user_id)->first();
        if (!$applicantData) {
            return back()->with('error', 'Applicant data not found.');
        }

        $applicantData->admin_check_cor = $request->has('admin_check_cor');
        $applicantData->admin_check_indigency = $request->has('admin_check_indigency');
        $applicantData->evaluated_by = $request->evaluated_by;
        $applicantData->regional_coordinator = $request->regional_coordinator;
        $applicantData->application_status = $request->application_status ?? $applicantData->application_status;
        $applicantData->admin_remarks = $request->admin_remarks;

        $applicantData->save();

        // Also update the application status if needed
        $application->status = $request->application_status ?? $application->status;
        $application->save();

        return back()->with('success', 'Application evaluation updated successfully.');
    }

    // --- CASE 2: STUDENT UPDATING THEIR OWN DATA ---
    if ($user->role === 'student') {
        if ($application->user_id !== $user->id) {
            abort(403, 'Unauthorized action. You can only edit your own application.');
        }

        // Check if the application status is pending
        $applicantData = ApplicantData::where('user_id', $user->id)->first();
        $currentStatus = $applicantData?->application_status ?? $application->status ?? 'pending';

        if ($currentStatus !== 'pending') {
            return back()->with('error', 'Your application has been ' . $currentStatus . '. You can no longer edit or update it.');
        }

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'maiden_name' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'sex' => 'required|in:male,female',
            'place_of_birth' => 'required|string|max:255',
            'pob_zip_code' => 'required|string|max:20',
            'permanent_address' => 'required|string|max:500',
            'zip_code' => 'required|string|max:20',
            'mobile_number' => 'required|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'school_name' => 'required|string|max:255',
            'school_id_number' => 'nullable|string|max:255',
            'course' => 'required|string|max:255',
            'school_sector' => 'required|in:public,private',
            'year_level' => 'required|integer|min:1|max:10',
            'school_address' => 'required|string|max:500',
            'citizenship' => 'nullable|string|max:255',
            'total_income' => 'nullable|numeric|min:0',
            'siblings_count' => 'nullable|integer|min:0',
            'applicant_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'indigency_certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'enrollment_proof' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'signature_file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $applicantData = ApplicantData::firstOrNew(['user_id' => $user->id]);

        $data = $request->except([
            'applicant_photo', 'indigency_certificate', 'enrollment_proof', 'signature_file', 'year_level'
        ]);
        $data['course'] = $request->course;
        $data['year_level'] = $request->year_level;
        $data['has_assistance'] = ($request->has_other_assistance == 'yes') ? 1 : 0;
        $data['citizenship'] = $request->citizenship ?? $applicantData->citizenship ?? 'Filipino';
        $data['date_accomplished'] = $applicantData->date_accomplished ?? now()->format('Y-m-d');

        if ($request->hasFile('applicant_photo')) {
            if ($applicantData->applicant_photo && file_exists(public_path($applicantData->applicant_photo))) {
                unlink(public_path($applicantData->applicant_photo));
            }
            $name = time().'_photo.'.$request->applicant_photo->extension();
            $request->applicant_photo->move(public_path('uploads/photos'), $name);
            $data['applicant_photo'] = 'uploads/photos/'.$name;
        }

        if ($request->hasFile('indigency_certificate')) {
            if ($applicantData->indigency_certificate && file_exists(public_path($applicantData->indigency_certificate))) {
                unlink(public_path($applicantData->indigency_certificate));
            }
            $name = time().'_indigency.'.$request->indigency_certificate->extension();
            $request->indigency_certificate->move(public_path('uploads/docs'), $name);
            $data['indigency_certificate'] = 'uploads/docs/'.$name;
        }

        if ($request->hasFile('enrollment_proof')) {
            if ($applicantData->enrollment_proof && file_exists(public_path($applicantData->enrollment_proof))) {
                unlink(public_path($applicantData->enrollment_proof));
            }
            $name = time().'_enroll.'.$request->enrollment_proof->extension();
            $request->enrollment_proof->move(public_path('uploads/docs'), $name);
            $data['enrollment_proof'] = 'uploads/docs/'.$name;
        }

        if ($request->hasFile('signature_file')) {
            if ($applicantData->signature_path && file_exists(public_path($applicantData->signature_path))) {
                unlink(public_path($applicantData->signature_path));
            }
            $name = time().'_sig.'.$request->signature_file->extension();
            $request->signature_file->move(public_path('uploads/sigs'), $name);
            $data['signature_path'] = 'uploads/sigs/'.$name;
        }

        ApplicantData::updateOrCreate(['user_id' => $user->id], $data);

        Application::updateOrCreate(
            ['user_id' => $user->id],
            [
                'course' => $request->course,
                'year_level' => $request->year_level,
                'status' => $application->status ?? 'pending',
            ]
        );

        return redirect()->route('student.dashboard')->with('success', 'Your application has been updated successfully.');
    }

    abort(403);
}

public function edit($id) {
    $application = Application::findOrFail($id);

    if (Auth::user()->role === 'student' && $application->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $applicantData = ApplicantData::where('user_id', $application->user_id)->first();

    return view('applications.create', compact('application', 'applicantData'));
}

public function userDestroy($id) {
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    $user = User::findOrFail($id);

    // Prevent deletion of the default admin account
    if ($user->email === 'admin@chedro.gov.ph') {
        return redirect()->route('admin.users')->with('error', 'Cannot delete the default admin account.');
    }

    // Delete associated applications and applicant data
    $applications = Application::where('user_id', $user->id)->get();
    foreach ($applications as $application) {
        // Delete associated files if they exist
        if ($application->photo_path && file_exists(public_path($application->photo_path))) {
            unlink(public_path($application->photo_path));
        }
        if ($application->signature_path && file_exists(public_path($application->signature_path))) {
            unlink(public_path($application->signature_path));
        }
        if ($application->document_path && file_exists(public_path($application->document_path))) {
            unlink(public_path($application->document_path));
        }
        $application->delete();
    }

    ApplicantData::where('user_id', $user->id)->delete();

    $user->delete();

    return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
}

}
