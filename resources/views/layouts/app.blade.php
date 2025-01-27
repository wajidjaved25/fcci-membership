<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCCI Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-lg font-bold">FCCI Portal</a>
            <div class="space-x-4">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <a href="{{ route('register.show', 'proprietorship') }}" class="hover:underline">Register</a>

                <!-- Logout Link -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:underline">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-10">
        @yield('content')
    </main>
</body>
</html>
