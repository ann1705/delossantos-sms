@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden" style="background: linear-gradient(135deg, #1A2236 0%, #232B43 100%);">
    <div class="glass-card max-w-md w-full p-10 rounded-3xl shadow-2xl relative z-10">
        <h2 class="text-3xl font-bold text-white text-center mb-2 font-sans">Sign In</h2>
        <p class="text-center mb-8" style="color: var(--color-muted);">Welcome back to UniFAST-TDP SMS</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-white mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition shadow-sm" style="focus-border-color: var(--color-accent);"
                       placeholder="name@example.com">
                @error('email') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="text-sm font-semibold text-white">Password</label>
                    <a href="{{ url('forgot-password') }}" class="text-xs font-bold transition underline" style="color: var(--color-accent);">
                        Forgot Password?
                    </a>
                </div>

                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition shadow-sm" style="focus-border-color: var(--color-accent);"
                           placeholder="••••••••">

                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none" style="color: var(--color-muted);">
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
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 shadow-sm" style="accent-color: var(--color-accent);">
                <label for="remember_me" class="ml-2 text-sm" style="color: var(--color-muted);">Remember me</label>
            </div>

            <button type="submit"
                    class="w-full text-gray-900 font-bold py-3.5 rounded-xl transition duration-200 shadow-lg active:scale-95 btn btn-accent">
                Sign In
            </button>

            <div class="mt-8 text-center text-sm" style="color: var(--color-muted);">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold hover:underline" style="color: var(--color-accent);">Apply for Scholarship</a>
            </div>
        </form>
    </div>

    <div class="absolute top-20 left-20 w-64 h-64 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="background-color: var(--color-accent);"></div>
    <div class="absolute bottom-20 right-20 w-64 h-64 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="background: linear-gradient(to right, var(--color-accent), #FFB400);"></div>
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
