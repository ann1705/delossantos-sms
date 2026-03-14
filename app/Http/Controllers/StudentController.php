<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index()
    {
        // Use ->first() instead of ->get() because the dashboard
        // expects a single object, not a collection list.
        $application = Application::where('user_id', Auth::id())->first();

        // We pass 'application' (singular) to match your Blade @if($application)
        return view('student.dashboard', compact('application'));
    }

    public function downloadPDF() {
        // This stays the same - it finds the student's record or shows a 404 if missing
        $application = Application::where('user_id', Auth::id())->firstOrFail();

        $pdf = Pdf::loadView('student.pdf_template', compact('application'));
        return $pdf->download('UniFAST_Application_'.Auth::user()->name.'.pdf');
    }
}
