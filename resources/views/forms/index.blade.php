@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen mt-12">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-blue-950 tracking-tight">Scholarship Forms</h1>
                <p class="text-gray-500 mt-1">Access and download official application documents.</p>
            </div>

            @if(Auth::check() && Auth::user()->role == 'admin')
            <button onclick="toggleModal(true)" class="bg-blue-600 text-white px-7 py-3 rounded-2xl shadow-xl hover:bg-blue-700 font-bold transition-all flex items-center gap-2 transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Upload New Form
            </button>
            @endif
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-in slide-in-from-top duration-500">
            <div class="bg-green-500 text-white p-1 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl animate-in slide-in-from-top duration-500">
            <ul class="list-disc list-inside font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($forms as $form)
            <div class="bg-white/80 backdrop-blur-xl p-7 rounded-[2rem] shadow-xl border border-white/40 flex flex-col justify-between hover:shadow-2xl hover:border-blue-200 transition-all duration-300 group">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-blue-50 p-4 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>

                        @if(Auth::check() && Auth::user()->role == 'admin')
                        <form action="{{ route('admin.forms.destroy', $form->id) }}" method="POST" onsubmit="return confirm('Permanently delete this form and file?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>

                    <h3 class="text-2xl font-extrabold text-gray-800 mb-2 group-hover:text-blue-900 transition-colors">{{ $form->title }}</h3>
                    <div class="flex items-center gap-2 mb-8">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter font-bold">Updated: {{ $form->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <a href="{{ route('forms.download', $form->id) }}" class="w-full bg-blue-600 text-white text-center py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-200 block active:scale-95">
                    Download Documents
                </a>
            </div>
            @empty
            <div class="col-span-full py-24 text-center bg-white/40 backdrop-blur-md rounded-[3rem] border-4 border-dashed border-blue-50">
                <div class="inline-flex bg-blue-50 p-6 rounded-full text-blue-300 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 00-2 2H6a2 2 0 00-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-gray-400 font-bold text-lg">No official forms are available yet.</p>
                <p class="text-gray-300 text-sm">Admins can upload forms using the button above.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@if(Auth::check() && Auth::user()->role == 'admin')
<div id="uploadModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-md transition-opacity" onclick="toggleModal(false)"></div>

        <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full p-10 animate-in fade-in zoom-in duration-300 border border-white/20">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-black text-blue-900">Upload Official Form</h3>
                    <p class="text-sm text-gray-400">Add a new document to the registry.</p>
                </div>
                <button onclick="toggleModal(false)" class="bg-gray-50 text-gray-400 hover:text-red-500 p-2 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('admin.forms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="handleFormSubmit(this)">
                @csrf
                <div>
                    <label class="block text-sm font-black text-gray-700 mb-3 ml-1">Form Title</label>
                    <input type="text" name="title" required placeholder="e.g., UniFAST-TDP Annex 1"
                           class="w-full px-5 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-0 outline-none transition-all bg-gray-50/50 font-semibold">
                </div>

                <div>
                    <label class="block text-sm font-black text-gray-700 mb-3 ml-1">Document File</label>
                    <div class="group relative border-2 border-dashed border-gray-200 rounded-[2rem] p-8 bg-gray-50/50 text-center hover:border-blue-400 transition-all">
                        <input type="file" name="file" accept=".pdf,.doc,.docx" required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2">
                            <div class="bg-white w-12 h-12 rounded-xl flex items-center justify-center mx-auto shadow-sm text-blue-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-600">Click to browse or drag file here</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">PDF, DOCX (Max 5MB)</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" id="submitBtn" class="w-full bg-blue-600 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 flex items-center justify-center gap-3">
                        <span>Confirm and Upload</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('uploadModal');
        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function handleFormSubmit(form) {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Uploading...</span>';
    }
</script>
@endif

@endsection
