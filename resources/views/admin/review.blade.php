@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <form action="{{ route('admin.applications.status', $application->id) }}" method="POST" class="space-y-8">
        @csrf @method('PATCH')

        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100">
            <div class="p-10 border-b border-gray-50 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-blue-950">Review Application</h1>
                    <p class="text-gray-500 font-bold italic">{{ $application->user->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.applications.pdf', $application->id) }}" class="bg-gray-900 text-white px-5 py-2 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-black transition shadow-lg">
                        Download PDF
                    </a>
                    <span class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest {{ $application->status == 'approved' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                        Current: {{ $application->status }}
                    </span>
                </div>
            </div>

            <div class="p-10 grid md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black uppercase text-blue-600 mb-2">Year Level</label>
                    <input type="text" name="year_level" value="{{ $application->year_level }}" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-blue-600 mb-2">Assigned Grant Amount</label>
                    <input type="number" name="grant_amount" value="{{ $application->grant_amount ?? 0 }}" class="w-full p-4 bg-gray-50 rounded-2xl border-none">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-blue-600 mb-2">COR Verified</label>
                    <input type="checkbox" name="admin_check_cor" value="1" {{ $applicantData->admin_check_cor ? 'checked' : '' }} class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase text-blue-600 mb-2">Indigency Verified</label>
                    <input type="checkbox" name="admin_check_indigency" value="1" {{ $applicantData->admin_check_indigency ? 'checked' : '' }} class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-blue-600 mb-2">Regional Coordinator</label>
                    <input type="text" name="regional_coordinator" value="{{ $application->regional_coordinator }}" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="p-10 bg-blue-50/50">
                <label class="block text-xs font-black uppercase text-blue-900 mb-4">Final Decision & Remarks</label>

                <div class="flex gap-4 mb-6">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="application_status" value="approved" class="hidden peer" {{ $application->status == 'approved' ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 border-white bg-white text-center font-bold text-gray-400 peer-checked:border-green-500 peer-checked:text-green-600 transition-all shadow-sm">Approve</div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="application_status" value="rejected" class="hidden peer" {{ $application->status == 'rejected' ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 border-white bg-white text-center font-bold text-gray-400 peer-checked:border-red-500 peer-checked:text-red-600 transition-all shadow-sm">Reject</div>
                    </label>
                </div>

                <textarea name="admin_remarks" rows="4" placeholder="State reason for accepting or declining..."
                          class="w-full p-6 rounded-3xl border-2 border-white outline-none focus:border-blue-500 bg-white shadow-inner font-medium">{{ $application->admin_remarks }}</textarea>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.registry') }}" class="flex-1 py-5 rounded-3xl bg-white text-gray-500 font-black text-center shadow-md hover:bg-gray-50">Back to Registry</a>
            <button type="submit" class="flex-[2] py-5 rounded-3xl bg-blue-600 text-white font-black shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all transform hover:-translate-y-1">Update Application</button>
        </div>
    </form>
</div>
@endsection
