@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <div class="glass max-w-md w-full p-10 rounded-3xl shadow-2xl border border-white/20 bg-white/40 backdrop-blur-md">
        <h2 class="text-3xl font-bold text-blue-950 text-center mb-4">Forgot Password?</h2>
        <p class="text-sm text-gray-600 text-center mb-8">
            No problem. Just let us know your email address and we will email you a password reset link.
        </p>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-blue-900 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-600 bg-white/60 shadow-sm"
                       placeholder="Enter your registered email">
                @error('email') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition duration-200 shadow-lg">
                Email Password Reset Link
            </button>

            <div class="mt-6 text-center text-sm">
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
