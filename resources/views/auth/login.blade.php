@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 relative overflow-hidden">
    <div class="glass max-w-md w-full p-10 rounded-3xl shadow-2xl border border-white/20 relative z-10 bg-white/40 backdrop-blur-md">
        <h2 class="text-3xl font-bold text-blue-950 text-center mb-8 font-sans">Sign In</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-blue-900 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-600 bg-white/60 transition shadow-sm"
                       placeholder="name@example.com">
                @error('email') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="text-sm font-semibold text-blue-900">Password</label>
                    <a href="{{ url('forgot-password') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition underline">
                        Forgot Password?
                    </a>
                </div>

                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-600 bg-white/60 transition shadow-sm"
                           placeholder="••••••••">

                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 focus:outline-none">
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.13-4.826M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M15.356 15.356A3 3 0 1111.414 11.414M21 21l-2-2m-3.998-3.998L3 3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center mb-6 mt-4">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition duration-200 shadow-lg active:scale-95">
                Sign In
            </button>

            <div class="mt-8 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Apply for Scholarship</a>
            </div>
        </form>
    </div>

    <div class="absolute top-20 left-20 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-64 h-64 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeOpen = document.querySelector('#eyeOpen');
        const eyeClosed = document.querySelector('#eyeClosed');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icons
            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        });
    });
</script>
@endsection
