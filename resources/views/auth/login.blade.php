<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - FCCI Membership Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .hover-animate {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .loading {
            pointer-events: none;
            opacity: 0.6;
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
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Login to FCCI Portal</h1>
            <p class="text-gray-600 text-center mb-8">Enter your mobile number and OTP to access your account.</p>

            <!-- Request OTP Form -->
            <form id="request-otp-form">
                @csrf
                <div class="mb-6">
                    <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                    <input type="text" id="mobile_number" name="mobile_number" class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your mobile number" required>
                </div>
                <div class="text-center">
                    <button type="submit" id="request-otp-btn" class="w-full px-4 py-2 bg-blue-500 text-white font-bold rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Request OTP
                    </button>
                </div>
            </form>

            <!-- OTP Verification Form -->
            <form id="verify-otp-form" style="display: none;">
                @csrf
                <input type="hidden" name="mobile_number" id="verify_mobile_number">
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">OTP</label>
                    <input type="text" id="otp" name="otp" class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your OTP" required>
                </div>
                <div class="text-center">
                    <button type="submit" id="verify-otp-btn" class="w-full px-4 py-2 bg-blue-500 text-white font-bold rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Verify OTP
                    </button>
                </div>
            </form>

            <!-- Modal -->
            <div id="modal" class="hidden fixed z-50 inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded shadow-lg text-center">
                    <p id="modal-message" class="text-lg font-medium"></p>
                    <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Close</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Script -->
    <script>
        const requestOtpForm = document.getElementById('request-otp-form');
        const verifyOtpForm = document.getElementById('verify-otp-form');
        const modal = document.getElementById('modal');
        const modalMessage = document.getElementById('modal-message');
        const requestOtpBtn = document.getElementById('request-otp-btn');
        const verifyOtpBtn = document.getElementById('verify-otp-btn');

        requestOtpForm.addEventListener('submit', function (e) {
            e.preventDefault();
            toggleLoading(requestOtpBtn, true);
            const mobileNumber = document.getElementById('mobile_number').value;

            axios.post('{{ route("login.request-otp") }}', { mobile_number: mobileNumber })
                .then(response => {
                    console.log('Response:', response.data); // Debugging the response
                    showModal(response.data.message);
                    document.getElementById('verify_mobile_number').value = mobileNumber;
                    requestOtpForm.style.display = 'none';
                    verifyOtpForm.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error.response); // Debugging the error
                    showModal(error.response?.data?.message || 'An error occurred. Please try again.');
                })
                .finally(() => toggleLoading(requestOtpBtn, false));
        });

        verifyOtpForm.addEventListener('submit', function (e) {
            e.preventDefault();
            toggleLoading(verifyOtpBtn, true);
            const mobileNumber = document.getElementById('verify_mobile_number').value;
            const otp = document.getElementById('otp').value;

            axios.post('{{ route("login.verify-otp") }}', { mobile_number: mobileNumber, otp: otp })
                .then(response => {
                    console.log('Redirect URL:', response.data.data.redirect_url); // Debugging the redirect URL
                    window.location.href = response.data.data.redirect_url;
                })
                .catch(error => {
                    console.error('Error during OTP verification:', error.response); // Debugging error
                    showModal(error.response?.data?.message || 'Invalid OTP. Please try again.');
                })
                .finally(() => toggleLoading(verifyOtpBtn, false));
        });

        function toggleLoading(button, isLoading) {
            button.classList.toggle('loading', isLoading);
            button.textContent = isLoading ? 'Processing...' : button.getAttribute('data-original-text') || button.textContent;
        }

        function showModal(message) {
            modalMessage.textContent = message;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
