@extends('layouts.app')

@section('content')
<section class="hero-section relative min-h-[70vh] flex items-center justify-center p-6">
    <div class="relative z-10 w-full max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
        <div class="flex-1">
            <div class="inline-block glass px-4 py-1.5 rounded-full text-sm font-semibold text-yellow-900 border border-yellow-200 mb-4">
                Officially Accredited Scholarship Program
            </div>
            <h1 class="hero-title">
                UniFAST-TDP Scholarship <br>Management System
            </h1>
            <div class="hero-subtitle max-w-lg">
                Streamlining the Tulong Dunong Program for tertiary education. Efficiently manage applications, verification, and status tracking for Filipino students.
            </div>
            <div class="pt-6">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-4 inline-block">
                            <p class="text-sm font-bold text-red-700">&#10060; ADMIN CAN'T APPLY</p>
                        </div>
                    @elseif(auth()->user()->role === 'secretary')
                        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-4 inline-block">
                            <p class="text-sm font-bold text-red-700">&#10060; SECRETARY CANT APPLY</p>
                        </div>
                    @else
                        <a href="{{ route('applications.create') }}" class="btn btn-accent text-lg px-10 py-4 shadow-lg">
                            Apply Now
                        </a>
                    @endif
                @else
                <a href="{{ route('register') }}" class="btn btn-accent text-lg px-10 py-4 shadow-lg">
                    Apply Now
                </a>
                @endauth
            </div>
        </div>
        <div class="flex-1 hidden md:flex items-center justify-center">
            <div class="glass-card p-10 flex flex-col items-center justify-center space-y-4 w-full max-w-md">
                <div class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <p class="text-lg font-semibold text-gray-900">Tertiary Education Support</p>
                <p class="text-sm text-gray-500 text-center">Tulong Dunong Program - TDP</p>
            </div>
        </div>
    </div>
    <div class="absolute top-10 right-10 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30"></div>
</section>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>

</div>
@endsection
