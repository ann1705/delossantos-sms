<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniFAST-TDP SMS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Glassmorphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Customizing Select2 to match Tailwind Design */
        .select2-container--default .select2-selection--single {
            border-radius: 0.75rem !important;
            padding: 0.6rem !important;
            height: auto !important;
            border: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
        }

        /* Smooth Navigation Transitions */
        .nav-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen font-sans antialiased text-gray-900">

    <nav class="fixed top-0 w-full z-50 glass px-6 py-3 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div class="group-hover:scale-110 transition-transform duration-300">
                <img src="{{ asset('images/ched.png') }}" alt="CHED Logo" class="w-10 h-10 object-contain drop-shadow-sm">
            </div>

            <div class="h-8 w-[1.5px] bg-gray-200 hidden md:block"></div>

            <div class="flex items-center gap-2">
                <img src="{{ asset('images/unifast.png') }}" alt="UniFAST Logo" class="w-8 h-8 object-contain opacity-90">
                <div class="flex flex-col leading-none">
                    <span class="text-lg font-black text-blue-900 tracking-tight">UniFAST-TDP</span>
                    <span class="text-[9px] font-bold text-blue-600 uppercase tracking-widest">Tertiary Education Support</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 items-center">
            <a href="{{ route('home') }}"
               class="nav-link px-4 py-2 rounded-xl text-sm font-bold
               {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                Home
            </a>

            <a href="{{ route('forms.index') }}"
               class="nav-link px-4 py-2 rounded-xl text-sm font-bold
               {{ request()->is('forms*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                Forms
            </a>

            @auth
                @if(Auth::user()->role == 'student')
                    <a href="{{ route('student.dashboard') }}"
                       class="nav-link px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2
                       {{ (request()->is('student/dashboard') || request()->is('applications*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Application
                    </a>
                @endif

                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.users') }}"
                       class="nav-link px-4 py-2 rounded-xl text-sm font-bold
                       {{ request()->is('admin/users*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.registry') }}"
                       class="nav-link px-4 py-2 rounded-xl text-sm font-bold
                       {{ (request()->is('admin/registry*') || request()->is('admin/applications*')) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        Registry
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="nav-link px-4 py-2 rounded-xl text-sm font-bold
                   {{ request()->is('profile*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest text-red-500 hover:bg-red-50 transition-all duration-200">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-bold px-4 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl shadow-lg hover:bg-blue-700 transition font-bold transform hover:scale-105">
                    Apply Now
                </a>
            @endauth
        </div>
    </nav>

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <div id="flash-container">
               @if(session('success'))
                <div id="successAlert" class="fixed top-24 right-8 z-[200] animate-in slide-in-from-right duration-300">
                    <div class="bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-bold tracking-tight">{{ session('success') }}</span>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        const alert = document.getElementById('successAlert');
                        if(alert) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateX(30px)';
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 4000);
                </script>
                @endif
            </div>

            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>
</html>
