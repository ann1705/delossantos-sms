@extends('layouts.app')

@section('content')
<div class="min-h-screen mt-12 px-6 py-8">
    <div class="max-w-7xl mx-auto glass rounded-3xl p-10 md:p-12">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">System Users</h1>
                <p class="text-muted font-medium text-lg" style="color: var(--color-muted);">Manage administrator and student accounts.</p>
            </div>

            <button onclick="toggleAddForm()" class="btn btn-accent p-4 shadow-lg h-fit">
                <svg id="plusIcon" class="w-7 h-7 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
            </button>
        </div>

        <!-- Search Section -->
        <div class="glass rounded-3xl p-8 mb-8">
            <div class="flex flex-col gap-3 mb-4">
                <p class="text-[11px] text-muted font-semibold" style="color: var(--color-muted);">Search users by name, email, or role</p>
            </div>

            <div class="relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted pointer-events-none" style="color: var(--color-muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <input type="text" id="userSearchInput" name="search" value="{{ request('search') }}" placeholder="🔍 Type to search users..."
                    class="w-full pl-12 pr-12 py-4 rounded-2xl border-2 border-gray-300 bg-white text-gray-800 placeholder-gray-500 outline-none focus:ring-4 focus:ring-opacity-30 transition-all duration-300 font-semibold shadow-sm" style="focus-border-color: var(--color-accent); focus-ring-color: var(--color-accent);">

                <button id="clearSearchBtn" type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 hidden p-2 text-gray-400 rounded-lg transition-all duration-200 hover:text-gray-600"
                    onclick="clearUserSearch()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Live Search Feedback -->
            <div id="userSearchFeedback" class="mt-4 flex items-center gap-2 text-sm font-semibold opacity-0 transition-opacity duration-300" style="color: var(--color-muted);">
                <span class="inline-block w-2 h-2 bg-accent rounded-full animate-pulse" style="background-color: var(--color-accent);"></span>
                <span id="userFeedbackText">Showing {{ $users->count() }} users.</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
            <div class="glass-card px-6 py-6">
                <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Users</span>
                <span class="text-3xl font-black text-gray-900">{{ $totalUsers ?? $users->count() }}</span>
            </div>
            <div class="glass-card px-6 py-6">
                <span class="block text-[10px] font-black uppercase tracking-widest" style="color: var(--color-accent);">Administrators</span>
                <span class="text-3xl font-black text-gray-900">{{ $adminCount ?? 0 }}</span>
            </div>
            <div class="glass-card px-6 py-6">
                <span class="block text-[10px] font-black uppercase tracking-widest" style="color: var(--color-accent);">Secretaries</span>
                <span class="text-3xl font-black text-gray-900">{{ $secretaryCount ?? 0 }}</span>
            </div>
            <div class="glass-card px-6 py-6">
                <span class="block text-[10px] font-black uppercase tracking-widest" style="color: var(--color-accent);">Students</span>
                <span class="text-3xl font-black text-gray-900">{{ $studentCount ?? 0 }}</span>
            </div>
        </div>

        <div id="createUserForm" class="{{ $errors->any() ? '' : 'hidden' }} glass-card rounded-3xl p-10 mb-12 animate-in fade-in zoom-in duration-300">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Add New User</h2>
                <p class="text-gray-600 font-medium">Register a new account manually.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-900 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-6 py-4 rounded-2xl border-2 border-gray-300 focus:border-accent outline-none bg-white font-semibold transition-all" style="focus-border-color: var(--color-accent);">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-900 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               class="w-full px-6 py-4 rounded-2xl border-2 border-gray-300 focus:border-accent outline-none bg-white font-semibold transition-all @error('email') border-red-500 @enderror" style="focus-border-color: var(--color-accent);">
                        @error('email') <p class="text-red-500 text-xs mt-1 font-bold">This email is already taken.</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-900 uppercase tracking-widest ml-1">Role</label>
                        <select name="role" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-300 focus:border-accent outline-none bg-white font-semibold" style="focus-border-color: var(--color-accent);">
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="secretary" {{ old('role') == 'secretary' ? 'selected' : '' }}>Secretary</option>
                        </select>
                    </div>

                    <div class="hidden md:block"></div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-900 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative">
                            <input id="pass" name="password" type="password" required
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-gray-300 focus:border-accent outline-none bg-white font-semibold @error('password') border-red-500 @enderror" style="focus-border-color: var(--color-accent);">
                            <button type="button" onclick="toggleText('pass', 'eye1')" class="absolute inset-y-0 right-4 flex items-center">
                                <svg id="eye1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 font-bold">Passwords do not match.</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-900 uppercase tracking-widest ml-1">Confirm Password</label>
                        <div class="relative">
                            <input id="conf" name="password_confirmation" type="password" required
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-gray-300 focus:border-accent outline-none bg-white font-semibold" style="focus-border-color: var(--color-accent);">
                            <button type="button" onclick="toggleText('conf', 'eye2')" class="absolute inset-y-0 right-4 flex items-center">
                                <svg id="eye2" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" class="btn btn-accent px-10 py-4 font-black uppercase tracking-widest shadow-lg">Create Account</button>
                    <button type="button" onclick="toggleAddForm()" class="btn px-10 py-4 text-gray-700 bg-gray-200 font-black uppercase tracking-widest hover:bg-gray-300 transition-all">Cancel</button>
                </div>
            </form>
        </div>

        <div class="glass-card rounded-3xl shadow-2xl overflow-hidden mb-12">
            <table class="w-full text-left">
                <thead class="border-b" style="border-bottom-color: #FFD600;">
                    <tr>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest">Full Name</th>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest">Email Address</th>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest text-center">Role</th>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="p-6 font-bold text-gray-900">{{ $user->name }}</td>
                        <td class="p-6 font-medium text-gray-600">{{ $user->email }}</td>
                        <td class="p-6 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest" style="background-color: rgba(255, 214, 0, 0.1); color: var(--color-accent);">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            @if($user->id !== auth()->id() && $user->email !== 'admin@chedro.gov.ph')
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete user permanently? This action cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="transition-all hover:scale-125" type="submit" style="color: var(--color-accent);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr data-empty="true"><td colspan="4" class="p-24 text-center text-gray-500 font-bold italic">No matching users found.</td></tr>
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

    function clearUserSearch() {
        const searchInput = document.getElementById('userSearchInput');
        searchInput.value = '';
        searchInput.focus();
        searchInput.dispatchEvent(new Event('input'));
    }

    window.onload = function() {
        const form = document.getElementById('createUserForm');
        if (!form.classList.contains('hidden')) {
            document.getElementById('plusIcon').style.transform = 'rotate(45deg)';
        }

        const searchInput = document.getElementById('userSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const tableBody = document.getElementById('usersTableBody');
        const feedbackDiv = document.getElementById('userSearchFeedback');
        const feedbackText = document.getElementById('userFeedbackText');

        function applyUserFilter() {
            const term = searchInput.value.toLowerCase().trim();
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            let visibleCount = 0;
            let emptyRow = null;

            rows.forEach(row => {
                if (row.dataset.empty === 'true') {
                    emptyRow = row;
                    return;
                }

                const name = row.cells[0]?.innerText.toLowerCase() || '';
                const email = row.cells[1]?.innerText.toLowerCase() || '';
                const role = row.cells[2]?.innerText.toLowerCase() || '';
                const matches = name.includes(term) || email.includes(term) || role.includes(term);

                row.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });

            if (emptyRow) {
                emptyRow.style.display = visibleCount === 0 ? '' : 'none';
            }

            // Update feedback text
            if (term === '') {
                feedbackText.innerText = `Showing ${visibleCount} users.`;
                feedbackDiv.style.opacity = '1';
            } else {
                feedbackText.innerText = `${visibleCount} user${visibleCount === 1 ? '' : 's'} found for "${term}".`;
                feedbackDiv.style.opacity = '1';
            }

            // Show/hide clear button
            clearBtn.style.display = term ? 'block' : 'none';
        }

        searchInput.addEventListener('input', applyUserFilter);
        applyUserFilter();
    }

</script>
@endsection
