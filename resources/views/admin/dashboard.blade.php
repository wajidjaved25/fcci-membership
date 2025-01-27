<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FCCI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-600 shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <img src="{{ asset('images/fcci-logo.png') }}" alt="FCCI Logo" class="h-16">
            <p class="text-white font-bold text-lg">Admin Dashboard</p>
            <div class="text-white flex items-center space-x-4">
                <p class="font-medium">Welcome, {{ Auth::user()->name }}</p>
                <!-- Logout Link -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-sm underline hover:text-gray-300">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">Pending Registrations</h1>

        @if($registrations->isEmpty())
            <div class="text-center p-6 bg-white rounded-lg shadow">
                <p class="text-lg text-gray-600">No pending registrations at the moment.</p>
            </div>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registrations as $registration)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $registration->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $registration->company_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $registration->status ?? 'Pending' }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.show', $registration->id) }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 focus:outline-none">
                                    View
                                </a>
                                <form action="{{ route('admin.approve', $registration->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 focus:outline-none">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.reject', $registration->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Rejected by admin">
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 focus:outline-none">
                                        Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 mt-10">
        <div class="container mx-auto px-4 py-6 text-center">
            <p class="text-sm text-gray-600">Powered by FCCI</p>
        </div>
    </footer>
</body>
</html>
