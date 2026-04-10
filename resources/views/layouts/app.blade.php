<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniFAST-TDP SMS</title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Global font import fallback */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

        /* (Reverted: No custom nav styles here) */
        :root {
            --color-primary: #1A2236;
            --color-secondary: #232B43;
            --color-accent: #FFD600;
            --color-accent-dark: #FFC400;
            --color-muted: #B0B8C1;
            --color-dark: #101624;
            --color-surface: #fff;
        }

        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
        }

        body, html {
            background: var(--color-primary);
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            letter-spacing: 0.01em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .glass {
            background: var(--color-secondary);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(16, 22, 36, 0.18);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .glass-card {
            background: #fff;
            color: #232B43;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px 0 rgba(16, 22, 36, 0.10);
            border: 1.5px solid var(--color-accent);
        }

        .btn-accent {
            background: var(--color-accent);
            color: #232B43;
            font-weight: bold;
            border-radius: 2rem;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px 0 rgba(255, 214, 0, 0.10);
            transition: background 0.2s, color 0.2s;
        }
        .btn-accent:hover {
            background: var(--color-accent-dark);
            color: #232B43;
        }

        .hero-section {
            background: linear-gradient(120deg, #1A2236 60%, #232B43 100%);
            padding: 4rem 0 3rem 0;
            border-radius: 0 0 2.5rem 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(16, 22, 36, 0.18);
        }
        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--color-muted);
            margin-bottom: 2.5rem;
            font-weight: 500;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            font-weight: 800;
            letter-spacing: -0.01em;
        }
        .card, .glass, .glass-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px 0 rgba(16, 22, 36, 0.10);
        }
        .btn, .btn-accent {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            font-weight: 700;
            border-radius: 2rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .btn-accent {
            background: var(--color-accent);
            color: #232B43;
            box-shadow: 0 2px 8px 0 rgba(255, 214, 0, 0.10);
        }
        .btn-accent:hover, .btn-accent:focus {
            background: var(--color-accent-dark);
            color: #232B43;
            box-shadow: 0 4px 16px 0 rgba(255, 214, 0, 0.18);
        }

        .nav-link {
            transition: all 0.2s ease-in-out;
            color: var(--color-muted);
            background: transparent;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
        }
        .nav-link:hover {
            background: rgba(255, 214, 0, 0.1);
            color: var(--color-accent);
        }
        .nav-link.active {
            background: var(--color-accent);
            color: #232B43;
            font-weight: 700;
        }
        nav {
            background: var(--color-dark) !important;
            border-bottom: 1px solid rgba(255, 214, 0, 0.15);
        }
        nav .text-lg {
            color: #fff !important;
        }
        nav .text-\[9px\] {
            color: var(--color-accent) !important;
        }
        nav .bg-gray-200 {
            background: rgba(255, 255, 255, 0.2) !important;
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .select2-container--default .select2-selection--single {
            border-radius: 0.75rem !important;
            padding: 0.6rem !important;
            height: auto !important;
            border: 1px solid rgba(36, 54, 68, 0.12) !important;
            background-color: rgba(255, 255, 255, 0.85) !important;
            color: var(--color-muted) !important;
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased">

    <nav class="fixed top-0 w-full z-50 px-6 py-3 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div class="group-hover:scale-110 transition-transform duration-300">
                <img src="{{ asset('images/ched.png') }}" alt="CHED Logo" class="w-10 h-10 object-contain drop-shadow-sm">
            </div>
            <div class="h-8 w-[1.5px] bg-gray-200 hidden md:block"></div>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/unifast.png') }}" alt="UniFAST Logo" class="w-8 h-8 object-contain opacity-90">
                <div class="flex flex-col leading-none">
                    <span class="text-lg font-black text-white tracking-tight">UniFAST-TDP</span>
                    <span class="text-[9px] font-bold uppercase tracking-widest">Tertiary Education Support</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 items-center">
            <a href="{{ route('home') }}" class="nav-link text-sm font-bold{{ request()->routeIs('home') ? ' active' : '' }}">Home</a>
            <a href="{{ route('forms.index') }}" class="nav-link text-sm font-bold{{ request()->is('forms*') ? ' active' : '' }}">Forms</a>

            @auth
                @if(Auth::user()->role == 'student')
                    <a href="{{ route('student.dashboard') }}" class="nav-link text-sm font-bold flex items-center gap-2{{ (request()->is('student/dashboard') || request()->is('applications*')) ? ' active' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Application
                    </a>
                @endif
                @if(in_array(Auth::user()->role, ['admin', 'secretary']))
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.users') }}" class="nav-link text-sm font-bold{{ request()->is('admin/users*') ? ' active' : '' }}">Users</a>
                    @endif
                    <a href="{{ route('admin.registry') }}" class="nav-link text-sm font-bold{{ (request()->is('admin/registry*') || request()->is('admin/applications*')) ? ' active' : '' }}">Registry</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="nav-link text-sm font-bold{{ request()->is('profile*') ? ' active' : '' }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="nav-link text-xs font-black uppercase tracking-widest" onclick="return confirm('Are you sure you want to logout?');">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link text-sm font-bold">Login</a>
                <a href="{{ route('register') }}" class="btn btn-accent text-xs font-black px-6 py-2">Apply Now</a>
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
