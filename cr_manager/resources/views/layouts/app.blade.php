<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Password Manager')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .transition-all {
            transition: all 0.3s ease;
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .strength-weak { background-color: #ef4444; width: 25%; }
        .strength-medium { background-color: #f59e0b; width: 50%; }
        .strength-strong { background-color: #10b981; width: 75%; }
        .strength-very-strong { background-color: #059669; width: 100%; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    @if(session('authenticated'))
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-white text-2xl mr-3"></i>
                    <h1 class="text-white text-xl font-bold">Password Manager</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-black bg-opacity-25' : '' }}">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                        <a href="{{ route('credentials.index') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('credentials.*') ? 'bg-black bg-opacity-25' : '' }}">
                            <i class="fas fa-key mr-1"></i> Credentials
                        </a>
                        <a href="{{ route('credit-cards.index') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('credit-cards.*') ? 'bg-black bg-opacity-25' : '' }}">
                            <i class="fas fa-credit-card mr-1"></i> Credit Cards
                        </a>
                        <a href="{{ route('secure-notes.index') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('secure-notes.*') ? 'bg-black bg-opacity-25' : '' }}">
                            <i class="fas fa-sticky-note mr-1"></i> Secure Notes
                        </a>
                        <a href="{{ route('categories.index') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-black bg-opacity-25' : '' }}">
                            <i class="fas fa-folder mr-1"></i> Categories
                        </a>
                    </div>
                    
                    <!-- Search -->
                    <div class="relative">
                        <form action="{{ route('credentials.search') }}" method="GET" class="flex">
                            <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}" 
                                   class="bg-white bg-opacity-20 text-white placeholder-gray-300 px-3 py-1 rounded-l-md text-sm focus:outline-none focus:bg-opacity-30">
                            <button type="submit" class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-r-md hover:bg-opacity-30">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            @foreach($errors->all() as $error)
                <span class="block sm:inline">{{ $error }}</span>
            @endforeach
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Copied to clipboard!');
            }).catch(() => {
                showNotification('Failed to copy to clipboard', 'error');
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Password visibility toggle
        function togglePasswordVisibility(button) {
            const input = button.previousElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Delete confirmation
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }
    </script>
    
    @stack('scripts')
</body>
</html>
