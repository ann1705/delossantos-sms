@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-indigo-50/50 py-10 px-4 mt-20">
    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-xl shadow-md border-t-[10px] border-blue-700 overflow-hidden">
            <div class="p-10 text-left">
                <h1 class="text-4xl font-normal text-gray-900 mb-6 tracking-tight">Scholarship Application Form</h1>

                <p class="text-base text-gray-800 mb-8">Your response has been recorded.</p>

                <div class="space-y-4">
                    <a href="{{ route('student.dashboard') }}" class="text-blue-600 underline text-sm hover:text-blue-800 transition">
                        Go back to Dashboard
                    </a>
                    <br>
                    <a href="{{ route('applications.create') }}" class="text-blue-600 underline text-sm hover:text-blue-800 transition">
                        Submit another response
                    </a>
                </div>
            </div>
        </div>

        <footer class="text-center py-10">
            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">
                This content is neither created nor endorsed by the Admin.
            </p>
        </footer>
    </div>
</div>
@endsection
