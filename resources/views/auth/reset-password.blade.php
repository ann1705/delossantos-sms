@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6" style="background: linear-gradient(135deg, #1A2236 0%, #232B43 100%);">
    <div class="glass-card max-w-md w-full p-10 rounded-3xl shadow-2xl relative z-10">
        <h2 class="text-3xl font-bold text-white text-center mb-8">Reset Password</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-white mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
                @error('email') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="code" class="block text-sm font-semibold text-white mb-2">Reset Code</label>
                <input id="code" type="text" name="code" value="{{ old('code') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition" style="focus-border-color: var(--color-accent);"
                       placeholder="Enter the 6-digit code from your email">
                @error('code') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-white mb-2">New Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
                @error('password') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-white mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
            </div>

            <button type="submit"
                    class="w-full text-gray-900 font-bold py-3.5 rounded-xl transition shadow-lg btn btn-accent">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
