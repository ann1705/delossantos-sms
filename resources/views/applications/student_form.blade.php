@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-indigo-50/50 py-10 px-4 mt-10">
    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-t-xl shadow-md border-t-[10px] border-blue-700 overflow-hidden mb-4">
            <div class="p-8">
                <h1 class="text-4xl font-normal text-gray-900 mb-4">Scholarship Application Form</h1>
                <p class="text-sm text-gray-700 mb-4">Please fill out this form to apply for the scholarship. Fields marked with <span class="text-red-600">*</span> are required.</p>
                <hr class="border-gray-200">
                <div class="mt-4 flex items-center text-sm font-bold text-gray-600">
                    {{ Auth::user()->email }} <span class="ml-2 text-blue-600 font-normal underline cursor-not-allowed">Logged in</span>
                </div>
            </div>
        </div>

        <form action="{{ route('applications.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-base font-medium text-gray-900 mb-6">
                    Full Name <span class="text-red-600">*</span>
                </label>
                <input type="text" name="student_name" required value="{{ Auth::user()->name }}"
                    class="w-full border-b border-gray-300 focus:border-blue-600 focus:outline-none py-2 text-gray-800 transition-all text-lg">
                <p class="text-xs text-gray-400 mt-2">Enter your legal name as it appears on school records.</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-base font-medium text-gray-900 mb-6">
                    What is your current Course? <span class="text-red-600">*</span>
                </label>
                <div class="space-y-4">
                    @foreach(['BSIT', 'BSCS', 'BSHM', 'BSBA'] as $course)
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="course" value="{{ $course }}" required class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-3 text-gray-700 group-hover:text-blue-600 transition">{{ $course }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-base font-medium text-gray-900 mb-6">
                    Latest General Weighted Average (GWA) <span class="text-red-600">*</span>
                </label>
                <input type="number" step="0.01" name="gwa" required placeholder="e.g. 1.50"
                    class="w-48 border-b border-gray-300 focus:border-blue-600 focus:outline-none py-2 text-gray-800 transition-all text-lg">
            </div>

            <div class="flex justify-between items-center py-6">
                <button type="submit"
                        class="bg-blue-700 text-white px-8 py-2.5 rounded font-medium hover:bg-blue-800 transition shadow-md active:scale-95">
                    Submit
                </button>
                <div class="flex items-center space-x-2">
                    <div class="w-32 bg-gray-200 h-2 rounded overflow-hidden">
                        <div class="bg-blue-700 h-2 rounded w-full"></div>
                    </div>
                    <span class="text-xs text-gray-500 font-medium tracking-tight">100% completed</span>
                </div>
            </div>
        </form>

        <footer class="text-center py-10">
            <p class="text-[10px] text-gray-500 uppercase tracking-widest">
                Scholarship Management System &copy; {{ date('Y') }}
            </p>
        </footer>
    </div>
</div>
@endsection
