@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen mt-12">
    <div class="max-w-3xl mx-auto">

        <a href="{{ route('admin.registry') }}" class="inline-flex items-center text-sm font-bold text-blue-600 mb-6 hover:translate-x-[-4px] transition-transform">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Registry
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
            <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">New Scholarship Application</h2>
                    <p class="text-blue-100 text-sm font-medium">Assign a new scholarship record to a student.</p>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
            </div>

            <form action="{{ route('applications.store') }}" method="POST" class="p-10 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Assign Student</label>
                    <select name="user_id" required class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 outline-none transition-all bg-gray-50/50 font-semibold text-gray-700">
                        <option value="" disabled selected>Select a student...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Course</label>
                        <input type="text" name="course" required placeholder="e.g. BSIT"
                               class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 outline-none transition-all bg-gray-50/50 font-semibold">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Year Level</label>
                        <select name="year_level" required class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 outline-none transition-all bg-gray-50/50 font-semibold">
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Semester</label>
                        <select name="semester" required class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 outline-none transition-all bg-gray-50/50 font-semibold">
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Initial Status</label>
                        <select name="status" required class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 outline-none transition-all bg-gray-50/50 font-semibold text-blue-600">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-3 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
