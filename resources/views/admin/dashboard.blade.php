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
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">Admin Dashboard</h1>

        <!-- Tabs for Switching Between Users & Registrations -->
        <div class="flex justify-center mb-6">
            <button onclick="showSection('registrations')" class="tab-btn bg-blue-500 text-white px-4 py-2 mx-2 rounded-lg">Registrations</button>
            <button onclick="showSection('users')" class="tab-btn bg-gray-500 text-white px-4 py-2 mx-2 rounded-lg">Users</button>
        </div>

        <!-- ✅ Pending Registrations Section -->
        <div id="registrations-section" class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Pending Registrations</h2>
            
            @if($registrations->isEmpty())
                <div class="text-center p-6 bg-gray-100 rounded-lg">
                    <p class="text-lg text-gray-600">No pending registrations at the moment.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrations as $registration)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $registration->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $registration->company_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $registration->status ?? 'Pending' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.show', $registration->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        View
                                    </a>
                                    <form action="{{ route('admin.approve', $registration->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reject', $registration->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="rejection_reason" value="Rejected by admin">
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                            Reject
                                        </button>
                                    </form>
                                    <a href="{{ route('registrations.download-pdf', $registration->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                        Download PDF
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- ✅ Users Section -->
        <div id="users-section" class="bg-white rounded-lg shadow p-6 hidden">
            <h2 class="text-xl font-bold text-gray-700 mb-4">All Users</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->mobile_number }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="#" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    Edit
                                </a>
                                <a href="#" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function showSection(section) {
            document.getElementById('registrations-section').classList.toggle('hidden', section !== 'registrations');
            document.getElementById('users-section').classList.toggle('hidden', section !== 'users');
        }
    </script>

</body>
</html>
