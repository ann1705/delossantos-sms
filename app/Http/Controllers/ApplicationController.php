<?php

namespace App\Http\Controllers;

use App\Models\ApplicantData;
use App\Models\ApplicationReview;
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

        $applications = ApplicantData::with(['user', 'latestReview'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('id', $search);
                })->orWhere('id', $search)
                  ->orWhere('school_name', 'like', "%{$search}%");
            })
            ->latest()->get();

        $message = null;
        $totalApplicants = $applications->count();
        $pendingCount = $applications->where('application_status', 'pending')->count();
        $approvedCount = $applications->where('application_status', 'approved')->count();
        $rejectedCount = $applications->where('application_status', 'rejected')->count();

        if ($search && $applications->isEmpty()) {
            $message = 'Keyword does not match';

            $totalApplicants = ApplicantData::count();
            $pendingCount = ApplicantData::where('application_status', 'pending')->count();
            $approvedCount = ApplicantData::where('application_status', 'approved')->count();
            $rejectedCount = ApplicantData::where('application_status', 'rejected')->count();
        }

        // Return JSON for API requests
        if ($request->wantsJson() || $request->is('api/*')) {
            $response = [
                'applications' => $applications,
                'total_applicant' => $totalApplicants,
                'pending' => $pendingCount,
                'approve' => $approvedCount,
                'rejected' => $rejectedCount,
            ];

            if ($message) {
                $response['message'] = $message;
            }

            return response()->json($response);
        }

        return view('admin.registry', compact('applications'));
    }

    /**
     * Show the form for creating a new application.
     */
    public function create()
    {
        $user = Auth::user();

        // Prevent admin/secretary accounts from accessing the application form
        if (in_array($user->role, ['admin', 'secretary'])) {
            return redirect()->route('admin.registry')->with('error', 'Admin and Secretary accounts cannot apply for scholarships.');
        }

        $application = null;
        $applicantData = null;
        $users = [];

        if ($user->role === 'student') {
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
        // Prevent admin/secretary accounts from submitting applications
        if (in_array(Auth::user()->role, ['admin', 'secretary'])) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Admin and Secretary accounts cannot submit scholarship applications.',
                ], 403);
            }

            return redirect()->route('admin.registry')->with('error', 'Admin and Secretary accounts cannot submit scholarship applications.');
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

        // Reset status to pending when student submits/updates
        if (Auth::user()->role === 'student') {
            $data['application_status'] = 'pending';
        }

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

        // 4. Save to ApplicantData (The main application record)
        $applicantData = ApplicantData::updateOrCreate(['user_id' => $targetUserId], $data);

        if ($request->has('download') && Auth::user()->role === 'student') {
            return $this->downloadPdf();
        }

        // Return JSON for API requests
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Application submitted successfully',
                'data' => $applicantData,
            ], 201);
        }

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

        $applicantData = ApplicantData::with('latestReview')->findOrFail($id);

        $newStatus = $request->application_status ?? $request->status ?? $applicantData->application_status;

        $reviewPayload = [
            'new_application_status' => $newStatus,
            'admin_remarks' => $request->admin_remarks,
            'evaluated_by' => $request->evaluated_by ?? auth()->user()->name,
            'admin_check_cor' => (int) $request->input('admin_check_cor', 0),
            'admin_check_indigency' => (int) $request->input('admin_check_indigency', 0),
            'regional_coordinator' => $request->regional_coordinator ?? auth()->user()->name,
        ];

        $latestReview = $applicantData->latestReview;

        if ($latestReview) {
            $latestReview->update($reviewPayload);
            $applicantData->update(['application_status' => $latestReview->new_application_status]);
        } else {
            $review = $applicantData->reviews()->create($reviewPayload);
            $applicantData->update(['application_status' => $review->new_application_status]);
        }

        if ($request->has('download')) {
            return $this->downloadAdminPdf($id);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Application for ' . $applicantData->first_name . ' has been updated.',
                'data' => $applicantData,
            ], 200);
        }

        return back()->with('success', 'Application for ' . $applicantData->first_name . ' has been updated.');
    }

    /**
     * Display a specific application for Admin review.
     */
    public function show($id)
    {
        // Fetch applicant data
        $applicantData = ApplicantData::with(['user', 'latestReview'])->find($id);

        if (!$applicantData) {
            if (request()->wantsJson() || request()->is('api/*')) {
                return response()->json(['error' => 'Application not found for ID ' . $id], 404);
            }
            return redirect()->route('admin.registry')->with('error', 'Application not found for ID ' . $id);
        }

        // Security: Prevent non-admin/non-secretary users from seeing other students' applications
        if (!in_array(Auth::user()->role, ['admin', 'secretary']) && $applicantData->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this application.');
        }

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'application' => $applicantData,
                'review' => $applicantData->latestReview ? $applicantData->latestReview->toArray() : null,
            ]);
        }

        $application = null; // Keep for backward compatibility in views

        return view('admin.review', compact('applicantData'));
    }

    /**
     * Get application data as JSON (for AJAX requests).
     */
    public function getApplicationData($id)
    {
        $applicantData = ApplicantData::with(['user', 'latestReview'])->find($id);

        if (!$applicantData) {
            return response()->json(['error' => 'Application not found'], 404);
        }

        // Security: Prevent non-admin/non-secretary users from seeing other students' applications
        if (!in_array(Auth::user()->role, ['admin', 'secretary']) && $applicantData->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'app' => [
                'id' => $applicantData->id,
                'user_id' => $applicantData->user_id,
                'application_status' => $applicantData->application_status,
            ],
            'data' => $applicantData->toArray(),
            'review' => $applicantData->latestReview ? $applicantData->latestReview->toArray() : null,
        ]);
    }

    /**
     * Delete an application.
     */
    public function destroy($id)
    {
        $applicantData = ApplicantData::findOrFail($id);

        // Check if user is admin/secretary or owns the application
        if (!in_array(Auth::user()->role, ['admin', 'secretary']) && $applicantData->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own application.');
        }

        // Students can only delete pending applications
        if (Auth::user()->role === 'student') {
            if ($applicantData->application_status !== 'pending') {
                if (request()->wantsJson() || request()->is('api/*')) {
                    return response()->json(['message' => 'You cannot delete an application that has been ' . $applicantData->application_status . '.'], 400);
                }
                return back()->with('error', 'You cannot delete an application that has been ' . $applicantData->application_status . '.');
            }
        }

        $applicantData->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Application deleted successfully'], 200);
        }

        return back()->with('success', 'Application deleted.');
    }

    /**
     * Delete an application as admin or secretary.
     */
    public function adminDestroy($id)
    {
        $applicantData = ApplicantData::findOrFail($id);

        if (!in_array(Auth::user()->role, ['admin', 'secretary'])) {
            abort(403, 'Unauthorized action.');
        }

        $applicantData->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Application deleted successfully by admin'], 200);
        }

        return redirect()->route('admin.registry')->with('success', 'Application deleted successfully.');
    }

    public function success()
    {
        return view('applications.success');
    }

    // --- USER MANAGEMENT METHODS ---

    public function userIndex(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $secretaryCount = User::where('role', 'secretary')->count();
        $studentCount = User::where('role', 'student')->count();
        $message = null;

        if ($search && $users->isEmpty()) {
            $message = 'Keyword does not match';
        }

        // Return JSON for API requests
        if ($request->wantsJson() || $request->is('api/*')) {
            $response = [
                'users' => $users,
                'total_users' => $totalUsers,
                'admin_count' => $adminCount,
                'secretary_count' => $secretaryCount,
                'student_count' => $studentCount,
            ];

            if ($message) {
                $response['message'] = $message;
            }

            return response()->json($response);
        }

        return view('admin.users', compact('users', 'totalUsers', 'adminCount', 'secretaryCount', 'studentCount', 'message'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,student,secretary',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'User created.', 'user' => $user], 201);
        }

        return redirect()->route('admin.users')->with('success', 'User created.');
    }

   public function viewForm()
{
    // Get the detailed data for the logged-in student
    $applicantData = ApplicantData::with('latestReview')->where('user_id', Auth::id())->first();
    $application = null; // Keep for backward compatibility

    if (!$applicantData) {
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['error' => 'No application data found.'], 404);
        }
        return redirect()->route('student.dashboard')->with('error', 'No application data found.');
    }

    if (request()->wantsJson() || request()->is('api/*')) {
        $defaults = [
            'id' => $applicantData->id,
            'user_id' => $applicantData->user_id,
            'applicant_photo' => $applicantData->applicant_photo,
            'last_name' => $applicantData->last_name,
            'first_name' => $applicantData->first_name,
            'middle_name' => $applicantData->middle_name,
            'maiden_name' => $applicantData->maiden_name,
            'dob' => $applicantData->dob,
            'sex' => $applicantData->sex,
            'place_of_birth' => $applicantData->place_of_birth,
            'pob_zip_code' => $applicantData->pob_zip_code,
            'permanent_address' => $applicantData->permanent_address,
            'zip_code' => $applicantData->zip_code,
            'citizenship' => $applicantData->citizenship,
            'tribal_membership' => $applicantData->tribal_membership,
            'mobile_number' => $applicantData->mobile_number,
            'email_address' => $applicantData->email_address,
            'disability_type' => $applicantData->disability_type,
            'school_name' => $applicantData->school_name,
            'course' => $applicantData->course,
            'school_id_number' => $applicantData->school_id_number,
            'school_address' => $applicantData->school_address,
            'school_sector' => $applicantData->school_sector,
            'year_level' => $applicantData->year_level,
            'father_status' => $applicantData->father_status,
            'father_name' => $applicantData->father_name,
            'father_address' => $applicantData->father_address,
            'father_occupation' => $applicantData->father_occupation,
            'mother_status' => $applicantData->mother_status,
            'mother_name' => $applicantData->mother_name,
            'mother_address' => $applicantData->mother_address,
            'mother_occupation' => $applicantData->mother_occupation,
            'total_income' => $applicantData->total_income,
            'siblings_count' => $applicantData->siblings_count,
            'has_assistance' => $applicantData->has_assistance,
            'application_status' => $applicantData->application_status,
            'admin_remarks' => $applicantData->admin_remarks,
            'evaluated_by' => $applicantData->evaluated_by,
            'admin_check_cor' => $applicantData->admin_check_cor,
            'admin_check_indigency' => $applicantData->admin_check_indigency,
            'regional_coordinator' => $applicantData->regional_coordinator,
            'enrollment_proof' => $applicantData->enrollment_proof,
            'indigency_certificate' => $applicantData->indigency_certificate,
            'signature_path' => $applicantData->signature_path,
            'date_accomplished' => $applicantData->date_accomplished,
            'created_at' => $applicantData->created_at,
            'updated_at' => $applicantData->updated_at,
        ];

        $applicationData = array_merge($defaults, $applicantData->toArray());

        array_walk($applicationData, function (&$value) {
            if ($value === null) {
                $value = '';
            }
        });

        return response()->json([
            'application' => $applicationData,
            'review' => $applicantData->latestReview ? $applicantData->latestReview->toArray() : null,
        ]);
    }

    return view('applications.view_form', compact('application', 'applicantData'));
}

    public function downloadPdf()
{
    $applicantData = ApplicantData::where('user_id', Auth::id())->firstOrFail();
    $application = null; // Keep for backward compatibility

    $pdf = Pdf::loadView('applications.view_form_pdf', compact('application', 'applicantData'))
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 150,
        ]);

    $filename = 'application_' . $applicantData->id . '.pdf';
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma' => 'public',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
    ]);
}

    public function downloadAdminPdf($id)
    {
        $applicantData = ApplicantData::with('user')->findOrFail($id);
        $application = null; // Keep for backward compatibility

        $pdf = Pdf::loadView('applications.view_form_pdf', compact('application', 'applicantData'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
            ]);

        $filename = 'application_' . $applicantData->id . '.pdf';
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    private function reviewHasChanges($latestReview, array $payload): bool
    {
        return $latestReview->new_application_status !== $payload['new_application_status']
            || trim((string) ($latestReview->admin_remarks ?? '')) !== trim((string) ($payload['admin_remarks'] ?? ''))
            || trim((string) ($latestReview->evaluated_by ?? '')) !== trim((string) ($payload['evaluated_by'] ?? ''))
            || $latestReview->admin_check_cor !== $payload['admin_check_cor']
            || $latestReview->admin_check_indigency !== $payload['admin_check_indigency']
            || trim((string) ($latestReview->regional_coordinator ?? '')) !== trim((string) ($payload['regional_coordinator'] ?? ''));
    }

    public function update(Request $request, $id)
{
    // 1. Find the applicant data
    $applicantData = ApplicantData::findOrFail($id);
    $user = auth()->user();

    // Editing the application is student-only.
    if (in_array($user->role, ['admin', 'secretary'])) {
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Application editing is student-only. Use /api/admin/applications/' . $id . '/status to review the application.',
            ], 403);
        }

        abort(403, 'Application editing is student-only. Use the review endpoint instead.');
    }

    if ($user->role === 'student') {
        if ($applicantData->user_id !== $user->id) {
            abort(403, 'Unauthorized action. You can only edit your own application.');
        }

        // Check if the application status is pending
        if ($applicantData->application_status !== 'pending') {
            return back()->with('error', 'Your application has been ' . $applicantData->application_status . '. You can no longer edit or update it.');
        }

        // Validate only the submitted fields so partial updates work over form-data
        $requestData = $request->except([
            'applicant_photo', 'indigency_certificate', 'enrollment_proof', 'signature_file'
        ]);

        // Build validation rules dynamically - required only if present in the request
        $rules = [
            'last_name' => 'sometimes|required|string|max:255',
            'first_name' => 'sometimes|required|string|max:255',
            'middle_name' => 'sometimes|nullable|string|max:255',
            'maiden_name' => 'sometimes|nullable|string|max:255',
            'dob' => 'sometimes|required|date',
            'sex' => 'sometimes|required|in:male,female',
            'place_of_birth' => 'sometimes|required|string|max:255',
            'pob_zip_code' => 'sometimes|required|string|max:20',
            'permanent_address' => 'sometimes|required|string|max:500',
            'zip_code' => 'sometimes|required|string|max:20',
            'mobile_number' => 'sometimes|required|string|max:20',
            'email_address' => 'sometimes|nullable|email|unique:applicant_data,email_address,' . $applicantData->id . '|max:255',
            'school_name' => 'sometimes|required|string|max:255',
            'school_id_number' => 'sometimes|nullable|string|max:255',
            'course' => 'sometimes|required|string|max:255',
            'school_sector' => 'sometimes|required|in:public,private',
            'year_level' => 'sometimes|required|integer|min:1|max:10',
            'school_address' => 'sometimes|required|string|max:500',
            'citizenship' => 'sometimes|nullable|string|max:255',
            'total_income' => 'sometimes|nullable|numeric|min:0',
            'siblings_count' => 'sometimes|nullable|integer|min:0',
        ];

        // Only validate file fields if they are present in the request
        if ($request->hasFile('applicant_photo')) {
            $rules['applicant_photo'] = 'image|mimes:jpg,jpeg,png|max:2048';
        }
        if ($request->hasFile('indigency_certificate')) {
            $rules['indigency_certificate'] = 'mimes:pdf,jpg,jpeg,png|max:5120';
        }
        if ($request->hasFile('enrollment_proof')) {
            $rules['enrollment_proof'] = 'mimes:pdf,jpg,jpeg,png|max:5120';
        }
        if ($request->hasFile('signature_file')) {
            $rules['signature_file'] = 'image|mimes:jpg,jpeg,png|max:2048';
        }

        // Validate only the request fields using Validator facade
        $validator = \Illuminate\Support\Facades\Validator::make($requestData, $rules);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $applicantData->toArray();

        // Apply submitted request values over existing values.
        $requestData = $request->except([
            'applicant_photo', 'indigency_certificate', 'enrollment_proof', 'signature_file'
        ]);

        foreach ($requestData as $key => $value) {
            $data[$key] = $value;
        }

        $data['course'] = $request->input('course', $applicantData->course);
        $data['year_level'] = $request->input('year_level', $applicantData->year_level);
        $data['has_assistance'] = $request->input('has_other_assistance') === 'yes' ? 1 : $applicantData->has_assistance;
        $data['citizenship'] = $request->input('citizenship', $applicantData->citizenship ?? 'Filipino');
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

        $applicantData->fill($data);
        $applicantData->save();

        if ($request->has('download')) {
            return $this->downloadPdf();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Your application has been updated successfully',
                'data' => $applicantData,
            ], 200);
        }

        return redirect()->route('student.dashboard')->with('success', 'Your application has been updated successfully.');
    }

    abort(403);
}

public function edit($id) {
    $applicantData = ApplicantData::findOrFail($id);

    if (Auth::user()->role === 'student' && $applicantData->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $application = null; // Keep for backward compatibility

    return view('applications.create', compact('application', 'applicantData'));
}

public function userDestroy(Request $request, $id) {
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    $user = User::findOrFail($id);

    // Prevent deletion of the default admin account
    if ($user->email === 'admin@chedro.gov.ph') {
        return redirect()->route('admin.users')->with('error', 'Cannot delete the default admin account.');
    }

    // Delete associated applicant data
    $applicantData = ApplicantData::where('user_id', $user->id)->first();
    if ($applicantData) {
        // Delete associated files if they exist
        if ($applicantData->applicant_photo && file_exists(public_path($applicantData->applicant_photo))) {
            unlink(public_path($applicantData->applicant_photo));
        }
        if ($applicantData->signature_path && file_exists(public_path($applicantData->signature_path))) {
            unlink(public_path($applicantData->signature_path));
        }
        if ($applicantData->enrollment_proof && file_exists(public_path($applicantData->enrollment_proof))) {
            unlink(public_path($applicantData->enrollment_proof));
        }
        if ($applicantData->indigency_certificate && file_exists(public_path($applicantData->indigency_certificate))) {
            unlink(public_path($applicantData->indigency_certificate));
        }
        $applicantData->delete();
    }

    $user->delete();

    if ($request->wantsJson() || $request->is('api/*')) {
        return response()->json(['message' => 'User deleted successfully.']);
    }

    return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
}

}
