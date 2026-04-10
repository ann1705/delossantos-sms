@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.registry') }}" class="flex items-center gap-2 font-bold transition" style="color: var(--color-muted);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Registry
        </a>
        <div class="flex gap-3">
            <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border
                {{ $applicantData->application_status == 'approved' ? 'style="background-color: #dcfce7; color: #15803d; border-color: #15803d;"' : ($applicantData->application_status == 'rejected' ? 'style="background-color: #fee2e2; color: #991b1b; border-color: #991b1b;"' : 'style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent); border-color: var(--color-accent);"') }}">
                Status: {{ $applicantData->application_status }}
            </span>
        </div>
    </div>

    <div class="glass rounded-3xl p-8 mb-8">
        <div class="flex items-center gap-6 mb-6">
            @if($application->user->profile_photo)
                <img src="{{ asset($application->user->profile_photo) }}"
                     class="w-40 h-40 rounded-3xl object-cover border-4 shadow-2xl" style="border-color: var(--color-accent);">
            @else
                <div class="w-40 h-40 rounded-3xl flex items-center justify-center shadow-2xl border-4" style="background-color: var(--color-secondary); border-color: var(--color-accent);">
                    <span class="text-6xl font-black text-white uppercase">
                        {{ substr($application->user->name ?? '?', 0, 1) }}
                    </span>
                </div>
            @endif
            <div class="flex-1">
                <h1 class="text-4xl font-black tracking-tight text-white">{{ $application->user->applicantData->first_name }} {{ $application->user->applicantData->last_name }}</h1>
                <p class="text-lg font-medium" style="color: var(--color-accent);">{{ $applicantData->course ?? 'N/A' }} — Year {{ $applicantData->year_level ?? 'N/A' }}</p>
                <div class="mt-4 flex flex-wrap gap-4">
                    <span class="rounded-lg text-sm font-bold flex items-center gap-2 px-4 py-1.5" style="background-color: rgba(255, 255, 255, 0.1); color: var(--color-muted);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                        {{ $application->user->email }}
                    </span>
                    <span class="rounded-lg text-sm font-bold flex items-center gap-2 px-4 py-1.5" style="background-color: rgba(255, 255, 255, 0.1); color: var(--color-muted);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                        {{ $application->user->applicantData->mobile_number }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-8 grid md:grid-cols-2 gap-12 glass rounded-3xl mb-8">
        <section>
            <h2 class="text-sm font-black uppercase tracking-widest mb-6 flex items-center gap-2 text-white">
                <span class="w-8 h-1 rounded-full" style="background-color: var(--color-accent);"></span>
                Personal Details
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between border-b pb-2" style="border-color: rgba(255, 255, 255, 0.1);">
                    <span class="font-bold text-xs uppercase" style="color: var(--color-muted);">Date of Birth</span>
                    <span class="text-white font-bold">{{ $application->user->applicantData->dob }}</span>
                </div>
                <div class="flex justify-between border-b pb-2" style="border-color: rgba(255, 255, 255, 0.1);">
                    <span class="font-bold text-xs uppercase" style="color: var(--color-muted);">Sex</span>
                    <span class="text-white font-bold uppercase">{{ $application->user->applicantData->sex }}</span>
                </div>
                <div class="flex justify-between border-b pb-2" style="border-color: rgba(255, 255, 255, 0.1);">
                    <span class="font-bold text-xs uppercase" style="color: var(--color-muted);">Address</span>
                    <span class="text-white font-bold text-right max-w-[200px]">{{ $application->user->applicantData->permanent_address }}</span>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-sm font-black uppercase tracking-widest mb-6 flex items-center gap-2 text-white">
                <span class="w-8 h-1 rounded-full" style="background-color: var(--color-accent);"></span>
                Financial Status
            </h2>
            <div class="glass-card rounded-2xl p-6">
                <div class="text-center">
                    <p class="text-xs font-black uppercase mb-1" style="color: var(--color-muted);">Total Family Income</p>
                    <p class="text-3xl font-black text-gray-900">₱{{ number_format($application->user->applicantData->total_income, 2) }}</p>
                </div>
                <div class="mt-6 pt-6 border-t grid grid-cols-2 text-center" style="border-color: rgba(0, 0, 0, 0.1);">
                    <div>
                        <p class="text-[10px] font-black uppercase mb-1" style="color: var(--color-muted);">Father Status</p>
                        <p class="font-bold text-gray-700">{{ $application->user->applicantData->father_status }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase mb-1" style="color: var(--color-muted);">Mother Status</p>
                        <p class="font-bold text-gray-700">{{ $application->user->applicantData->mother_status }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="md:col-span-2 flex flex-col items-center py-10 rounded-3xl border-2 border-dashed" style="background-color: rgba(255, 214, 0, 0.05); border-color: rgba(255, 214, 0, 0.3);">
            <p class="text-xs font-black uppercase tracking-widest mb-4" style="color: var(--color-accent);">Applicant Signature</p>
            <img src="{{ asset($application->user->applicantData->signature_path) }}" class="h-32 object-contain">
            <p class="mt-4 text-sm font-bold" style="color: var(--color-muted);">Accomplished on {{ $application->user->applicantData->date_accomplished }}</p>
        </section>
    </div>

    <div class="flex flex-col md:flex-row gap-4 items-center justify-center" style="border-top: 1px solid rgba(255, 255, 255, 0.1);" *>
        <form action="{{ route('admin.applications.status', $application->id) }}" method="POST" class="w-full md:w-auto">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="approved">
            <button type="submit" class="w-full text-white px-12 py-4 rounded-2xl font-black transition shadow-lg flex items-center justify-center gap-2" style="background-color: #15803d;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                Approve Application
            </button>
        </form>

        <form action="{{ route('admin.applications.status', $application->id) }}" method="POST" class="w-full md:w-auto">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="w-full text-white px-12 py-4 rounded-2xl font-black transition shadow-lg flex items-center justify-center gap-2" style="background-color: #DC2626;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reject Application
            </button>
        </form>
    </div>
</div>
@endsection
