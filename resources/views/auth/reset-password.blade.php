@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <div class="glass max-w-md w-full p-10 rounded-3xl shadow-2xl border border-white/20 bg-white/40 backdrop-blur-md">
        <h2 class="text-3xl font-bold text-blue-950 text-center mb-8">Reset Password</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-blue-900 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                       class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-600 bg-white/60">
                @error('email') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-blue-900 mb-2">New Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-600 bg-white/60">
                @error('password') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-blue-900 mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-600 bg-white/60">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
