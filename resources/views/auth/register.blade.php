@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 px-6 lg:px-8" style="background: linear-gradient(135deg, #1A2236 0%, #232B43 100%);">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="p-3 rounded-2xl shadow-lg" style="background-color: var(--color-accent);">
                <svg class="w-10 h-10 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Create your account</h2>
        <p class="mt-2 text-center text-sm" style="color: var(--color-muted);">Apply for UniFAST-TDP Scholarship</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="glass-card py-8 px-4 sm:rounded-3xl sm:px-10">
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray ml-1">Full Name</label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none transition sm:text-sm" style="focus-border-color: var(--color-accent);">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray ml-1">Email address</label>
                    <input name="email" type="email" required value="{{ old('email') }}"
                           class="mt-1 block w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-xl focus:outline-none transition sm:text-sm" style="focus-border-color: var(--color-accent);">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1 font-semibold">This email is already registered.</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray ml-1">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none transition sm:text-sm" style="focus-border-color: var(--color-accent);">
                        <button type="button" onclick="toggleVisibility('password', 'eye1')" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="color: var(--color-muted);">
                            <svg id="eye1" class="h-5 w-5 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray ml-1">Confirm Password</label>
                    <div class="mt-1 relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none transition sm:text-sm" style="focus-border-color: var(--color-accent);">
                        <button type="button" onclick="toggleVisibility('password_confirmation', 'eye2')" class="absolute inset-y-0 right-0 pr-3 flex items-center" style="color: var(--color-muted);">
                            <svg id="eye2" class="h-5 w-5 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 text-gray-900 rounded-xl font-bold transition shadow-lg active:scale-95 btn btn-accent">
                    Register Account
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full" style="border-top: 1px solid var(--color-muted);"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-2" style="background-color: #ffffff; color: var(--color-muted);">Already have an account?</span></div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border border-gray-400 rounded-xl shadow-sm text-sm font-bold transition-all" style="background-color: rgba(255, 255, 255, 0.05); color: var(--color-muted);">
                        Sign in instead
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.style.color = 'var(--color-accent)';
        } else {
            input.type = 'password';
            icon.style.color = 'var(--color-muted)';
        }
    }
</script>
@endsection
