@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.registry') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Registry
        </a>
        <div class="flex gap-3">
            <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border
                {{ $application->status == 'approved' ? 'bg-green-100 text-green-700 border-green-200' : ($application->status == 'rejected' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200') }}">
                Status: {{ $application->status }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-10 text-white flex flex-col md:flex-row items-center gap-8">
            <div class="relative">
                @if($application->user->profile_photo)
                    <img src="{{ asset($application->user->profile_photo) }}"
                         class="w-40 h-40 rounded-3xl object-cover border-4 border-white shadow-2xl">
                @else
                    <div class="w-40 h-40 rounded-3xl bg-white/20 border-4 border-white shadow-2xl flex items-center justify-center">
                        <span class="text-6xl font-black text-white uppercase">
                            {{ substr($application->user->name ?? '?', 0, 1) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="text-center md:text-left">
                <h1 class="text-4xl font-black tracking-tight">{{ $application->user->applicantData->first_name }} {{ $application->user->applicantData->last_name }}</h1>
                <p class="text-blue-100 text-lg font-medium">{{ $application->course }} — Year {{ $application->year_level }}</p>
                <div class="mt-4 flex flex-wrap gap-4 justify-center md:justify-start">
                    <span class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                        {{ $application->user->email }}
                    </span>
                    <span class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                        {{ $application->user->applicantData->mobile_number }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-10 grid md:grid-cols-2 gap-12">
            <section>
                <h2 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                    Personal Details
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-400 font-bold text-xs uppercase">Date of Birth</span>
                        <span class="text-gray-800 font-bold">{{ $application->user->applicantData->dob }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-400 font-bold text-xs uppercase">Sex</span>
                        <span class="text-gray-800 font-bold uppercase">{{ $application->user->applicantData->sex }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-400 font-bold text-xs uppercase">Address</span>
                        <span class="text-gray-800 font-bold text-right max-w-[200px]">{{ $application->user->applicantData->permanent_address }}</span>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-8 h-1 bg-indigo-600 rounded-full"></span>
                    Financial Status
                </h2>
                <div class="bg-gray-50 p-6 rounded-2xl">
                    <div class="text-center">
                        <p class="text-xs text-gray-400 font-black uppercase mb-1">Total Family Income</p>
                        <p class="text-3xl font-black text-gray-900">₱{{ number_format($application->user->applicantData->total_income, 2) }}</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-2 text-center">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase">Father Status</p>
                            <p class="font-bold text-gray-700">{{ $application->user->applicantData->father_status }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase">Mother Status</p>
                            <p class="font-bold text-gray-700">{{ $application->user->applicantData->mother_status }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="md:col-span-2 flex flex-col items-center py-10 bg-blue-50/50 rounded-3xl border-2 border-dashed border-blue-100">
                <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-4">Applicant Signature</p>
                <img src="{{ asset($application->user->applicantData->signature_path) }}" class="h-32 object-contain mix-blend-multiply">
                <p class="mt-4 text-sm font-bold text-gray-500">Accomplished on {{ $application->user->applicantData->date_accomplished }}</p>
            </section>
        </div>

        <div class="bg-gray-50 p-8 flex flex-col md:flex-row gap-4 items-center justify-center border-t border-gray-100">
            <form action="{{ route('admin.applications.status', $application->id) }}" method="POST" class="w-full md:w-auto">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="w-full bg-green-600 text-white px-12 py-4 rounded-2xl font-black hover:bg-green-700 transition shadow-lg shadow-green-200 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    Approve Application
                </button>
            </form>

            <form action="{{ route('admin.applications.status', $application->id) }}" method="POST" class="w-full md:w-auto">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="w-full bg-white border-2 border-red-100 text-red-500 px-12 py-4 rounded-2xl font-black hover:bg-red-50 hover:border-red-200 transition flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reject Application
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
