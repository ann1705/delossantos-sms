@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen mt-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-blue-950 tracking-tight">Scholarship Registry</h1>
                <p class="text-gray-500 font-medium text-lg">Manage and track student scholarship records.</p>
            </div>

            <div class="flex items-center gap-4">
                <form action="{{ route('admin.registry') }}" method="GET" class="relative group flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..."
                           class="pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:w-80 outline-none w-64 transition-all duration-300 bg-white shadow-sm font-semibold text-gray-700">
                    <svg class="w-5 h-5 absolute left-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>

                    @if(request('search'))
                        <a href="{{ route('admin.registry') }}" class="absolute right-4 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        </a>
                    @endif
                </form>

                <a href="{{ route('applications.create') }}" class="bg-blue-600 text-white p-4 rounded-2xl shadow-xl hover:bg-blue-700 hover:rotate-90 transition-all duration-300 active:scale-90">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 animate-in fade-in duration-500">
            <table class="w-full text-left">
                <thead class="bg-blue-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest">Student</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest">Course</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest text-center">Status</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($applications as $app)
                    <tr class="hover:bg-blue-50/20 transition-all group">
                        <td class="p-6">
                            <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $app->user->name ?? 'Deleted Student' }}</div>
                        </td>
                        <td class="p-6 font-semibold text-gray-500 italic">{{ $app->course }} - Year {{ $app->year_level }}</td>
                        <td class="p-6 text-center">
                            <span class="px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm
                                {{ $app->status == 'approved' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $app->status }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <form action="{{ route('applications.destroy', $app->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-red-500 transition-all hover:scale-125">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-24 text-center text-gray-400 font-bold text-xl italic">No records found matching your search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
