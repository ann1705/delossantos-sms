@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center p-6 bg-gradient-to-br from-white via-blue-50 to-indigo-50">

    <div class="glass relative z-10 w-full max-w-7xl mx-auto rounded-3xl shadow-2xl overflow-hidden p-10 md:p-16">

        <div class="grid md:grid-cols-2 gap-12 items-center">

            <div class="space-y-6">
                <div class="inline-block glass px-4 py-1.5 rounded-full text-sm font-semibold text-blue-800 border-blue-200">
                    Officially Accredited Scholarship Program
                </div>

                <h1 class="text-5xl lg:text-6xl font-extrabold text-blue-950 leading-tight">
                    UniFAST-TDP Scholarship <br>Management System
                </h1>

                <p class="text-xl text-gray-700 max-w-lg leading-relaxed">
                    Streamlining the Tulong Dunong Program for tertiary education. Efficiently manage applications, verification, and status tracking for Filipino students.
                </p>

                <div class="pt-6">
                    <a href="{{ route('register') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-10 py-4 rounded-xl shadow-lg transition">
                        Apply Now
                    </a>
                </div>
            </div>

            <div class="hidden md:block">
                <div class="glass p-8 rounded-3xl aspect-square flex flex-col items-center justify-center space-y-4">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <p class="text-lg font-semibold text-blue-900">Tertiary Education Support</p>
                    <p class="text-sm text-gray-500 text-center">Tulong Dunong Program - TDP</p>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute top-10 right-10 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>

</div>
@endsection
