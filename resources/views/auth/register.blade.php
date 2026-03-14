@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-indigo-50/50 flex flex-col justify-center py-12 px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-200">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Create your account</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl border border-gray-100 sm:rounded-3xl sm:px-10">
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1">Full Name</label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1">Email address</label>
                    <input name="email" type="email" required value="{{ old('email') }}"
                           class="mt-1 block w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-semibold">This email is already registered.</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <button type="button" onclick="toggleVisibility('password', 'eye1')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="eye1" class="h-5 w-5 text-gray-400 hover:text-blue-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1">Confirm Password</label>
                    <div class="mt-1 relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <button type="button" onclick="toggleVisibility('password_confirmation', 'eye2')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="eye2" class="h-5 w-5 text-gray-400 hover:text-blue-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg active:scale-95">
                    Register Account
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">Already have an account?</span></div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-all">
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
            icon.classList.replace('text-gray-400', 'text-blue-600');
        } else {
            input.type = 'password';
            icon.classList.replace('text-blue-600', 'text-gray-400');
        }
    }
</script>
@endsection
