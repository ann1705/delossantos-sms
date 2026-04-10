@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    @php $review = $applicantData->latestReview; @endphp
    <form action="{{ route('admin.applications.status', $applicantData->id) }}" method="POST" class="space-y-8">
        @csrf @method('PATCH')

        <div class="glass rounded-3xl overflow-hidden">
            <div class="p-10 border-b border-white border-opacity-10 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-white">Review Application</h1>
                    <p class="text-sm font-bold italic" style="color: var(--color-muted);">{{ $applicantData->user->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.applications.pdf', $applicantData->id) }}" class="btn btn-accent text-xs" style="padding: 0.5rem 1rem;">
                        Download PDF
                    </a>
                    <span class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest {{ ($review->new_application_status ?? $applicantData->application_status) == 'approved' ? 'bg-green-600/40 text-green-300' : 'bg-yellow-600/40 text-yellow-300' }}">
                        Current: {{ strtoupper($review->new_application_status ?? $applicantData->application_status) }}
                    </span>
                </div>
            </div>

            <div class="p-10 grid md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-2">Year Level</label>
                    <input type="text" name="year_level" value="{{ $applicantData->year_level }}" class="w-full p-3 bg-white/10 border border-white/20 rounded-xl outline-none focus:border-white/40 text-white placeholder-gray-400 backdrop-blur-sm">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-2">Assigned Grant Amount</label>
                    <input type="number" name="grant_amount" value="{{ $applicantData->grant_amount ?? 0 }}" class="w-full p-3 bg-white/10 border border-white/20 rounded-xl outline-none focus:border-white/40 text-white placeholder-gray-400 backdrop-blur-sm">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-2">COR Verified</label>
                    <input type="checkbox" name="admin_check_cor" value="1" {{ optional($review)->admin_check_cor || $applicantData->admin_check_cor ? 'checked' : '' }} class="w-6 h-6" style="accent-color: var(--color-accent);">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-white mb-2">Indigency Verified</label>
                    <input type="checkbox" name="admin_check_indigency" value="1" {{ optional($review)->admin_check_indigency || $applicantData->admin_check_indigency ? 'checked' : '' }} class="w-6 h-6" style="accent-color: var(--color-accent);">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-white mb-2">Regional Coordinator</label>
                    <input type="text" name="regional_coordinator" value="{{ optional($review)->regional_coordinator ?? $applicantData->regional_coordinator }}" class="w-full p-3 bg-white/10 border border-white/20 rounded-xl outline-none focus:border-white/40 text-white placeholder-gray-400 backdrop-blur-sm">
                </div>
            </div>

            <div class="p-10 border-t border-white border-opacity-10">
                <label class="block text-xs font-black uppercase text-white mb-4">Final Decision & Remarks</label>

                <div class="flex gap-4 mb-6">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="application_status" value="approved" class="hidden peer" {{ ($review->new_application_status ?? $applicantData->application_status) == 'approved' ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 bg-white/5 border-white/20 text-center font-bold text-white peer-checked:border-green-500 peer-checked:bg-green-500/20 peer-checked:text-green-300 transition-all">Approve</div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="application_status" value="rejected" class="hidden peer" {{ ($review->new_application_status ?? $applicantData->application_status) == 'rejected' ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 bg-white/5 border-white/20 text-center font-bold text-white peer-checked:border-red-500 peer-checked:bg-red-500/20 peer-checked:text-red-300 transition-all">Reject</div>
                    </label>
                </div>

                <textarea name="admin_remarks" rows="4" placeholder="State reason for accepting or declining..."
                          class="w-full p-4 rounded-2xl border border-white/20 outline-none focus:border-white/40 bg-white/10 backdrop-blur-sm text-white placeholder-gray-400 font-medium">{{ optional($review)->admin_remarks ?? $applicantData->admin_remarks }}</textarea>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('admin.registry') }}" class="flex-1 py-4 rounded-2xl bg-white/5 border border-white/20 text-white font-black text-center hover:bg-white/10 transition">Back to Registry</a>
            <div class="flex-1 grid grid-cols-2 gap-4">
                <button type="submit" class="py-4 rounded-2xl btn btn-accent font-black transition-all">Update</button>
                <button type="submit" name="download" value="1" class="py-4 rounded-2xl bg-green-600 hover:bg-green-700 text-white font-black transition-all">Download PDF</button>
            </div>
        </div>
    </form>
</div>
@endsection
