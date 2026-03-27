@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen mt-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-blue-950 tracking-tight">System Users</h1>
                <p class="text-gray-500 font-medium text-lg">Manage administrator and student accounts.</p>
            </div>

            <div class="flex items-center gap-4">
                <form action="{{ route('admin.users') }}" method="GET" class="relative group flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search accounts..."
                           class="pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:w-80 outline-none w-64 transition-all duration-300 bg-white shadow-sm font-semibold">
                    <svg class="w-5 h-5 absolute left-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>

                <button onclick="toggleAddForm()" class="bg-blue-600 text-white p-4 rounded-2xl shadow-xl hover:bg-blue-700 transition-all duration-300 transform active:scale-95">
                    <svg id="plusIcon" class="w-7 h-7 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div id="createUserForm" class="{{ $errors->any() ? '' : 'hidden' }} bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 p-10 mb-12 animate-in fade-in zoom-in duration-300">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-blue-950 tracking-tight">Add New User</h2>
                <p class="text-gray-500 font-medium">Register a new account manually.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-blue-900 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 focus:border-blue-500 outline-none bg-gray-50/50 font-semibold transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-blue-900 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 focus:border-blue-500 outline-none bg-gray-50/50 font-semibold transition-all @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1 font-bold">This email is already taken.</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-blue-900 uppercase tracking-widest ml-1">Role</label>
                        <select name="role" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 focus:border-blue-500 outline-none bg-gray-50/50 font-semibold">
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div class="hidden md:block"></div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-blue-900 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative">
                            <input id="pass" name="password" type="password" required
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 focus:border-blue-500 outline-none bg-gray-50/50 font-semibold @error('password') border-red-500 @enderror">
                            <button type="button" onclick="toggleText('pass', 'eye1')" class="absolute inset-y-0 right-4 flex items-center">
                                <svg id="eye1" class="w-6 h-6 text-gray-300 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 font-bold">Passwords do not match.</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-blue-900 uppercase tracking-widest ml-1">Confirm Password</label>
                        <div class="relative">
                            <input id="conf" name="password_confirmation" type="password" required
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 focus:border-blue-500 outline-none bg-gray-50/50 font-semibold">
                            <button type="button" onclick="toggleText('conf', 'eye2')" class="absolute inset-y-0 right-4 flex items-center">
                                <svg id="eye2" class="w-6 h-6 text-gray-300 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all">Create Account</button>
                    <button type="button" onclick="toggleAddForm()" class="bg-gray-100 text-gray-500 px-10 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Cancel</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 mb-12">
            <table class="w-full text-left">
                <thead class="bg-blue-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest">Full Name</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest">Email Address</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest text-center">Role</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/20 transition-all">
                        <td class="p-6 font-bold text-gray-800">{{ $user->name }}</td>
                        <td class="p-6 font-medium text-gray-500">{{ $user->email }}</td>
                        <td class="p-6 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $user->role == 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            @if($user->id !== auth()->id() && $user->email !== 'admin@chedro.gov.ph')
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-red-500 transition-all hover:scale-125">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-24 text-center text-gray-400 font-bold italic">No matching users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleAddForm() {
        const form = document.getElementById('createUserForm');
        const icon = document.getElementById('plusIcon');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            icon.style.transform = 'rotate(45deg)';
        } else {
            form.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    function toggleText(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('text-gray-300', 'text-blue-500');
        } else {
            input.type = 'password';
            icon.classList.replace('text-blue-500', 'text-gray-300');
        }
    }

    window.onload = function() {
        const form = document.getElementById('createUserForm');
        if (!form.classList.contains('hidden')) {
            document.getElementById('plusIcon').style.transform = 'rotate(45deg)';
        }
    }
</script>
@endsection
