@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6" style="background: linear-gradient(135deg, #1A2236 0%, #232B43 100%);">
    <div class="glass-card max-w-md w-full p-10 rounded-3xl shadow-2xl relative z-10">
        <h2 class="text-3xl font-bold text-white text-center mb-4">Forgot Password?</h2>
        <p class="text-sm text-center mb-8" style="color: var(--color-muted);">
            No problem. Just let us know your email address and we will email you a password reset code.
        </p>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm p-3 rounded-lg" style="background-color: rgba(34, 197, 94, 0.1); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.3);">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 font-medium text-sm p-3 rounded-lg" style="background-color: rgba(220, 38, 38, 0.1); color: #fca5a5; border: 1px solid rgba(220, 38, 38, 0.3);">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ session('code_verified') ? route('password.store') : (session('code_sent') ? route('password.verify') : route('password.email')) }}">
            @csrf

            {{-- Always preserve email through the flow --}}
            @if(session('code_sent') || session('code_verified'))
            <input type="hidden" name="email" value="{{ session('reset_email') ?? old('email') }}">
            @endif

            @if(!session('code_sent'))
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-white mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition shadow-sm" style="focus-border-color: var(--color-accent);"
                       placeholder="Enter your registered email">
                @error('email') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>
            @endif

            @if(session('code_sent') && !session('code_verified'))
            <div class="mb-6">
                <label for="code" class="block text-sm font-semibold text-white mb-2">Reset Code</label>
                <input id="code" type="text" name="code" value="{{ old('code') }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition shadow-sm" style="focus-border-color: var(--color-accent);"
                       placeholder="Enter the 6-digit code from your email">
                @error('code') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>
            @endif

            @if(session('code_verified') || $errors->has('password') || $errors->has('password_confirmation'))
            <div class="mb-6">
                <label for="code" class="block text-sm font-semibold text-white mb-2">Reset Code</label>
                <input id="code" type="text" name="code" value="{{ session('reset_code') ?? old('code') }}" required readonly
                       class="w-full px-4 py-3 rounded-xl border border-gray-400 shadow-sm" style="background-color: #232B43; color: var(--color-muted);"
                       placeholder="Enter the 6-digit code from your email">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-white mb-2">New Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" value="{{ old('password') }}" required autofocus
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition shadow-sm pr-12" style="focus-border-color: var(--color-accent);"
                           placeholder="Enter your new password (min. 8 characters)">
                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                            onclick="togglePassword('password')">
                        <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password') <span class="text-red-400 text-xs mt-1 font-medium block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-semibold text-white mb-2">Confirm Password</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition shadow-sm pr-12" style="focus-border-color: var(--color-accent);"
                           placeholder="Confirm your new password">
                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                            onclick="togglePassword('password_confirmation')">
                        <svg id="password_confirmation-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation') <span class="text-red-400 text-xs mt-1 font-medium block">{{ $message }}</span> @enderror
            </div>
            @endif

            <button type="submit"
                    class="w-full text-gray-900 font-bold py-3.5 rounded-xl transition duration-200 shadow-lg btn btn-accent">
                @if(session('code_verified'))
                    Reset Password
                @elseif(session('code_sent'))
                    Verify Code
                @else
                    Email Password Reset Code
                @endif
            </button>

            <div class="mt-6 text-center text-sm">
                <a href="{{ route('login') }}" class="font-bold hover:underline" style="color: var(--color-accent);">Back to Login</a>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + '-eye');

    if (field.type === 'password') {
        field.type = 'text';
        // Change to eye-off icon
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.5 3.5 0 11-4.95 4.95m2.975-1.475L9 9m7 7l-7-7"></path>';
    } else {
        field.type = 'password';
        // Change back to eye icon
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}
</script>
@endsection
