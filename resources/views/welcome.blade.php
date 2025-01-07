<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to the FCCI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js" defer></script>
    <style>
        .hover-animate {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-600 shadow">
        <div class="container mx-auto px-4 py-6 flex justify-center">
            <img src="{{ asset('images/fcci-logo.png') }}" alt="FCCI Logo" class="h-16">
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Side Panel -->
            <aside class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md hover-animate mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Membership Categories</h2>
                    <ul class="list-disc pl-6 text-gray-600 space-y-2">
                        <li>Proprietorship Firm</li>
                        <li>Partnership and AOP Firm</li>
                        <li>Limited / Private Limited</li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover-animate mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Links</h2>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register.show', 'proprietorship') }}" class="text-blue-500 hover:underline">Register for Proprietorship</a></li>
                        <li><a href="{{ route('register.show', 'partnership') }}" class="text-blue-500 hover:underline">Register for Partnership</a></li>
                        <li><a href="{{ route('register.show', 'limited company') }}" class="text-blue-500 hover:underline">Register for Limited</a></li>
                        <li><a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a></li>
                        <li><a href="{{ url('/support') }}" class="text-blue-500 hover:underline">Support</a></li>
                    </ul>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                <div class="text-center mb-8">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">Welcome to the FCCI Membership Portal</h1>
                    <p class="text-base lg:text-lg text-gray-600">Streamline your membership management with ease. Register today to access exclusive benefits and services!</p>
                </div>

                <!-- Sections -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow-md hover-animate">
                        <h2 class="text-lg lg:text-2xl font-bold text-gray-800 mb-4">Proprietorship Firm</h2>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2">
                            <li>Copy of national tax certificate in favour of the firm (Photocopy).</li>
                            <li>Copy of CNIC of the proprietor.</li>
                            <li>Bank certificate.</li>
                            <li>Provide the latest tax return for the current year.</li>
                            <li>Sales tax registration along with the annual tax return.</li>
                        </ul>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md hover-animate">
                        <h2 class="text-lg lg:text-2xl font-bold text-gray-800 mb-4">Partnership and AOP Firm</h2>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2">
                            <li>National Tax certificate of the firm (Photocopy).</li>
                            <li>Copy of Partnership deed attested by Notary Public.</li>
                            <li>Registration certificate "C" issued by the registrar of the firm.</li>
                            <li>Photocopies of the CNIC of partners of the firm.</li>
                            <li>Bank certificate.</li>
                            <li>Income tax return of the current year of the company/firm.</li>
                        </ul>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md hover-animate">
                        <h2 class="text-lg lg:text-2xl font-bold text-gray-800 mb-4">Limited / Private Limited</h2>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2">
                            <li>NTN certificate in favour of the company.</li>
                            <li>Memorandum and articles of association attested by the registrar.</li>
                            <li>List of directors along with their signatures.</li>
                            <li>Certificate of incorporation.</li>
                            <li>Bank certificate for operating accounts.</li>
                            <li>Sales tax registration along with the annual return.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 mt-10">
        <div class="container mx-auto px-4 py-6 text-center">
            <p class="text-sm text-gray-600">Powered by</p>
            <a href="https://smsapp.pk" target="_blank">
                <img src="{{ asset('images/company-logo.png') }}" alt="Company Logo" class="h-12 mx-auto">
            </a>
            <p class="text-sm text-gray-600">Pakistan's First & Indigenous Business Communication Platform</p>
            <p class="text-sm text-gray-600">& Customer Experience Management Platform</p>
        </div>
    </footer>
</body>
</html>
