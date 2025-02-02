<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCCI - Portal</title>

    <!-- TailwindCSS and AlpineJS CDN Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js"></script>

    <style>
        /* Custom styles */
        .header-bg {
            background: linear-gradient(to right, #3b82f6, #9333ea);
        }

        .button:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
   <!-- Header Section -->
   <header class="header-bg shadow-xl">
        <div class="container mx-auto px-6 py-6 flex justify-between items-center">
            <!-- FCCI Logo Centered -->
            <div class="flex-1 flex justify-center">
                <img src="{{ asset('images/fcci-logo.png') }}" alt="FCCI Logo" class="h-16">
                </div>

            <!-- Logout Button (Right Corner) -->
            <div class="flex justify-end">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="px-4 py-2 bg-red-600 text-white rounded">
                    Logout
                </a>
            </div>
        </div>
    </header>
<body>
    <main class="container mx-auto px-4 py-10">
        @yield('content')
    </main>
</body>
</html>
