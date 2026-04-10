@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 mt-12">
    <div class="max-w-2xl mx-auto">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 glass-card border-l-4 p-6 shadow-sm" style="border-left-color: var(--color-accent);">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color: var(--color-accent);">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-8 glass-card border-l-4 p-6 shadow-sm" style="border-left-color: #EF4444;">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-700 mb-2">There were some errors:</h3>
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profile Header -->


        <!-- Profile Information Card -->
        <div class="glass-card rounded-3xl p-8 mb-8 fade-in-up">
            <div class="flex items-center mb-8 gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                    <p class="text-sm" style="color: var(--color-muted);">Update your personal details and profile photo</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    @method('PUT')
                <div class="flex flex-col items-center mb-10">
                    <div class="relative mb-4">
                        @if($user->profile_photo)
                            <img id="photoPreview" src="{{ asset($user->profile_photo) }}" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 shadow-lg" style="border-color: var(--color-accent);">
                        @else
                            <div id="photoPreview" class="w-32 h-32 rounded-full border-4 shadow-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, var(--color-secondary), #1A2236); border-color: var(--color-accent);">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-muted);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <label class="absolute bottom-0 right-0 text-white p-3 rounded-full cursor-pointer shadow-lg transition btn-accent" style="background-color: var(--color-accent); color: #232B43;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                        </label>
                    </div>
                    <p class="text-sm" style="color: var(--color-muted);">Click the camera icon to change your photo</p>
                </div>

                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-black mb-2">Full Name</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-8">
                    <label for="email" class="block text-sm font-medium text-black mb-2">Email Address</label>
                    <div class="relative">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full btn btn-accent py-3 px-6 rounded-lg font-medium shadow-lg hover:shadow-xl transition duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Profile Changes
                </button>
            </form>
        </div>

        <!-- Password Security Card -->
        <div class="glass-card rounded-3xl p-8 mb-8 fade-in-up">
            <div class="flex items-center mb-8 gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-900">Password & Security</h2>
                    <p class="text-sm" style="color: var(--color-muted);">Keep your account secure with a strong password</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- New Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-black mb-2">New Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="passwordEye" class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-muted);">                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-sm font-medium text-black mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none transition" style="focus-border-color: var(--color-accent);">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="confirmEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full btn btn-accent py-3 px-6 rounded-lg font-medium shadow-lg hover:shadow-xl transition duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Password
                </button>
            </form>
        </div>

        <!-- Danger Zone Card -->
        <div class="glass-card rounded-3xl p-8 mb-8">
            <div class="flex items-center mb-6 gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(239, 68, 68, 0.1); color: #EF4444;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-900">Danger Zone</h2>
                    <p class="text-sm" style="color: var(--color-muted);">Irreversible and destructive actions</p>
                </div>
            </div>

            <div class="rounded-lg p-4 mb-6" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-black-200">Delete Account</h3>
                        <p class="text-sm text-black-100 mt-1">Once you delete your account, there is no going back. Please be certain.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone and will permanently delete your account.');">
                @csrf
                @method('DELETE')

                <div class="mb-6">
                    <label for="delete_password" class="block text-sm font-medium text-black mb-2">Enter your password to confirm</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="delete_password"
                            name="password"
                            required
                            class="w-full pl-4 pr-12 py-3 border border-red-500 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none transition">
                        <button type="button" onclick="togglePassword('delete_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="deleteEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full text-white py-3 px-6 rounded-xl font-medium shadow-xl hover:shadow-2xl transition duration-200 flex items-center justify-center" style="background: linear-gradient(to right, #EF4444, #DC2626); color: white;">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Account Permanently
                </button>
            </form>
        </div>

    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    let eyeIcon;

    // Map field IDs to eye icons
    if (fieldId === 'password') {
        eyeIcon = document.getElementById('passwordEye');
    } else if (fieldId === 'password_confirmation') {
        eyeIcon = document.getElementById('confirmEye');
    } else if (fieldId === 'delete_password') {
        eyeIcon = document.getElementById('deleteEye');
    }

    if (field.type === 'password') {
        field.type = 'text';
        // Change to eye-slash icon (password visible)
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>';
    } else {
        field.type = 'password';
        // Change to eye icon (password hidden)
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}

function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'photoPreview';
                img.src = e.target.result;
                img.className = 'w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
