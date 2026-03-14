@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-12 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-xl shadow-sm border-t-8 border-blue-600 p-8 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 italic">Manage your scholarship applications and requirements here.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h2 class="text-xl font-bold text-blue-800 mb-4">Scholarship Application</h2>
                <p class="text-gray-600 mb-6">Ready to apply? Fill out the official form to submit your details for review.</p>

                {{-- This will take them to the Google Form style create page --}}
                <a href="{{ route('applications.create') }}"
   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">
   Apply Now
</a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Application Status</h2>
                <div class="flex items-center">
                    @php
                        // Fetch the latest application for this user
                        $application = \App\Models\Application::where('user_id', Auth::id())->latest()->first();
                    @endphp

                    @if($application)
                        @if($application->status == 'pending')
                            <span class="px-4 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700 uppercase">
                                ⏳ Pending Review
                            </span>
                        @elseif($application->status == 'approved')
                            <span class="px-4 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700 uppercase">
                                ✅ Approved
                            </span>
                        @else
                            <span class="px-4 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700 uppercase">
                                ❌ Rejected
                            </span>
                        @endif
                    @else
                        <span class="px-4 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-500">
                            No Application Found
                        </span>
                    @endif
                </div>

                @if($application)
                    <p class="text-sm text-gray-600 mt-4">Course: <strong>{{ $application->course }}</strong></p>
                @endif

                <p class="text-xs text-gray-400 mt-4 italic">Last updated: {{ date('F d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
