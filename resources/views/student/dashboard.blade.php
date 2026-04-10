@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 mt-10">
    <div class="glass rounded-3xl p-10 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20" style="color: var(--color-accent);">
                <path d="M10.394 2.827a1 1 0 00-.788 0L2.606 6.221A1 1 0 003 8v7a1 1 0 001 1h12a1 1 0 001-1V8a1 1 0 00.394-1.779l-7-3.394zM7 14V9h6v5H7z"></path>
            </svg>
        </div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-white mb-2">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-muted text-lg font-medium italic" style="color: var(--color-muted);">Manage your scholarship applications and requirements here.</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="glass-card p-8 rounded-3xl hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 shadow-sm" style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Scholarship Application</h2>
            <p class="text-gray-700 mb-8 leading-relaxed">Ready to apply or need to update your info? Your progress is saved automatically.</p>

            @php
                $currentStatus = $application?->application_status ?? 'pending';
                $isApproved = $currentStatus === 'approved';
                $isRejected = $currentStatus === 'rejected';
                $isPending = $currentStatus === 'pending';
            @endphp

            <div class="space-y-3">
                <a href="{{ route('applications.create') }}"
                   class="btn btn-accent inline-flex items-center justify-center w-full text-lg shadow-lg group {{ $isApproved ? 'opacity-50 cursor-not-allowed' : '' }}"
                   {{ $isApproved ? 'onclick="event.preventDefault(); alert(\'Your application has been approved. You cannot apply again.\');"' : '' }}>
                    {{ !$application ? 'Apply Now' : ($isRejected ? 'Apply Again' : 'Update Application') }}
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>

                @if($application && $isPending)
                    <form action="{{ route('applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your application? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-center text-xs font-black uppercase tracking-widest transition py-2" style="color: var(--color-accent);">
                            Delete My Application
                        </button>
                    </form>
                @elseif($application && !$isPending)
                    <div class="w-full text-center text-xs font-black uppercase text-gray-500 tracking-widest py-2">
                        Status: {{ ucfirst($currentStatus) }} - Cannot be modified
                    </div>
                @endif
            </div>
        </div>

        <div class="glass-card p-8 rounded-3xl flex flex-col">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-4">Application Status</h2>

            @php
                $latestReview = optional($application)->latestReview;
                $displayStatus = $latestReview?->new_application_status ?? $application?->application_status;
                $adminRemarks = $latestReview?->admin_remarks ?? $application?->admin_remarks;
                $corVerified = $latestReview?->admin_check_cor ?? $application?->admin_check_cor;
                $indigencyVerified = $latestReview?->admin_check_indigency ?? $application?->admin_check_indigency;
            @endphp

            @if($application)
                <div class="mt-4 glass-card rounded-3xl overflow-hidden">
                    <div class="p-6 flex items-center justify-between border-b" style="border-bottom-color: #FFD600;">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">Current Status</h3>
                        <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                            {{ $displayStatus == 'approved' ? 'style="background-color: #dcfce7; color: #15803d;"' : ($displayStatus == 'rejected' ? 'style="background-color: #fee2e2; color: #991b1b;"' : 'style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);"') }}>
                            {{ ucfirst($displayStatus ?? 'pending') }}
                        </span>
                    </div>

                    <div class="p-6">
                        @if($adminRemarks)
                            <div class="p-4 rounded-2xl border-l-4 mb-4" style="background-color: rgba(255, 214, 0, 0.05); border-left-color: var(--color-accent);">
                                <p class="text-[10px] font-black uppercase mb-1" style="color: var(--color-accent);">Message from Admin:</p>
                                <p class="text-sm text-gray-700 font-medium italic">"{{ $adminRemarks }}"</p>
                            </div>
                        @endif

                        @if($corVerified || $indigencyVerified)
                            <div class="p-4 rounded-2xl border-l-4 mb-4" style="background-color: #dcfce7; border-left-color: #15803d;">
                                <p class="text-[10px] font-black text-green-700 uppercase mb-2">Verified Requirements:</p>
                                <div class="space-y-1">
                                    @if($corVerified)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span class="text-xs font-bold text-gray-700">CORs / COEs Verified</span>
                                        </div>
                                    @endif
                                    @if($indigencyVerified)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span class="text-xs font-bold text-gray-700">Indigency Certificate Verified</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('applications.view_form') }}"
                    class="flex items-center justify-center gap-2 w-full py-3 text-white rounded-xl text-xs font-black uppercase tracking-widest transition btn-accent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        View Submitted Form
                    </a>
                    </div>
                </div>
            @else
                <div class="rounded-2xl p-4 flex items-center gap-3 mt-4" style="background-color: rgba(255, 214, 0, 0.05); border: 1px solid rgba(255, 214, 0, 0.2);">
                    <span class="w-3 h-3 rounded-full animate-pulse" style="background-color: var(--color-accent);"></span>
                    <span class="text-sm font-bold uppercase tracking-widest" style="color: var(--color-muted);">No Application Found</span>
                </div>
            @endif

            <div class="mt-auto pt-6 flex justify-between items-center" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <span class="text-[10px] uppercase font-bold tracking-tighter italic" style="color: var(--color-muted);">Last check: {{ date('M d, Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
