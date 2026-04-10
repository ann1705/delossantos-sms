@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-20" style="background: linear-gradient(135deg, #1A2236 0%, #232B43 100%);">
    <div class="max-w-xl w-full">
        <div class="glass-card rounded-3xl shadow-2xl p-12 text-center">
            <div class="mb-8">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full mx-auto mb-6" style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
                <h1 class="text-3xl font-black text-gray-900 mb-4">Application Submitted</h1>
                <p class="text-sm text-gray-700">Your scholarship application has been successfully submitted.</p>
            </div>

            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center justify-center w-full rounded-2xl btn btn-accent px-6 py-4 text-sm font-bold uppercase tracking-widest shadow-lg transition">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
