<style>
    body { font-family: sans-serif; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
    .content { margin-top: 30px; }
</style>
<div class="header">
    <h1>UniFAST-TDP SCHOLARSHIP RECORD</h1>
    <p>Academic Year 2025-2026</p>
</div>
<div class="content">
    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
    <p><strong>Course:</strong> {{ $application->applicantData->course }}</p>
    <p><strong>Student ID:</strong> {{ $application->student_id_no }}</p>
    <p><strong>Status:</strong> {{ strtoupper($application->status) }}</p>
</div>
