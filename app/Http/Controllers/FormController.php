<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::latest()->get();
        return view('forms.index', compact('forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        if ($request->hasFile('file')) {
            // This saves to storage/app/public/forms/
            $path = $request->file('file')->store('forms', 'public');

            Form::create([
                'title' => $request->title,
                'file_path' => $path,
            ]);

            return back()->with('success', 'Form uploaded successfully!');
        }
        return back()->with('error', 'No file selected.');
    }

    /**
     * Finalized Download Method
     */
    public function download($id)
    {
        $form = Form::findOrFail($id);

        // Ensure we are looking at the relative path from the 'public' disk root
        // If your DB has 'public/forms/file.pdf', this strips the 'public/' prefix
        $relativeWeight = str_replace('public/', '', $form->file_path);

        // Check if file exists using the Storage Facade
        if (Storage::disk('public')->exists($relativeWeight)) {

            // Get the full system path for the file
            $fullPath = storage_path('app/public/' . $relativeWeight);

            // Extract the extension (pdf, docx, etc.)
            $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

            // Create a clean name: "Scholarship_Form.pdf"
            $friendlyName = str_replace(' ', '_', $form->title) . '.' . $extension;

            // Return a direct download response
            return response()->download($fullPath, $friendlyName);
        }

        // Error fallback
        Log::error("Download failed. Physical file missing at: " . storage_path('app/public/' . $relativeWeight));
        return back()->with('error', 'Sorry, the file does not exist on the server disk.');
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        // Delete physical file
        if (Storage::disk('public')->exists($form->file_path)) {
            Storage::disk('public')->delete($form->file_path);
        }

        // Delete DB record
        $form->delete();

        return back()->with('success', 'Form and associated file deleted.');
    }
}
