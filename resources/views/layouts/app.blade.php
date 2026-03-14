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
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Customizing Select2 to match Tailwind Design */
        .select2-container--default .select2-selection--single {
            border-radius: 0.75rem !important;
            padding: 0.6rem !important;
            height: auto !important;
            border: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 12px !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen font-sans antialiased text-gray-900">

   <nav class="fixed top-0 w-full z-50 glass px-6 py-4 flex justify-between items-center shadow-sm">
    <div class="flex items-center gap-2">
        <div class="bg-blue-600 p-1.5 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <span class="text-xl font-bold text-blue-900 tracking-tight">UniFAST-TDP</span>
    </div>

    <div class="flex gap-6 items-center">
    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Home</a>
    <a href="{{ route('forms.index') }}" class="text-gray-600 hover:text-blue-600 font-medium transition flex items-center gap-1">
        Forms
    </a>

    @auth
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Users</a>
            <a href="{{ route('admin.registry') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Registry</a>
        @endif

        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Profile</a>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-red-500 font-semibold hover:text-red-700 transition">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Login</a>
        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded-xl shadow-md hover:bg-blue-700 transition font-bold">
            Apply Now
        </a>
    @endauth
</div>
</nav>

    <main class="pt-28 pb-12 px-6">
        <div class="max-w-7xl mx-auto">

            <div id="flash-container">
               @if(session('success'))
<div id="successAlert" class="fixed top-20 right-5 z-[200] animate-in slide-in-from-right duration-300">
    <div class="bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
</div>

<script>
    setTimeout(() => {
        document.getElementById('successAlert').style.display = 'none';
    }, 4000);
</script>
@endif

                @if($errors->any())
                    <div class="flash-message mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-xl shadow-md">
                        <ul class="list-disc list-inside font-medium text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @yield('content')

        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Automatically hide flash messages after 5 seconds
            setTimeout(function() {
                $('.flash-message').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
