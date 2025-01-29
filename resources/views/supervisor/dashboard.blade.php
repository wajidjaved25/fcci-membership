@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-center text-3xl font-bold text-gray-800">Membership Supervisor Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-lg font-bold text-gray-700 mb-4">Pending Membership Applications</h2>

        @if($registrations->isEmpty())
            <p class="text-center text-gray-600">No pending applications at the moment.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Company Name</th>
                            <th class="border border-gray-300 px-4 py-2">Status</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                        <tr class="text-center bg-white hover:bg-gray-50 transition duration-150">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $registration->id }}</td>
                            <td class="border border-gray-300 px-4 py-2 font-medium">{{ $registration->company_name }}</td>
                            <td class="border border-gray-300 px-4 py-2 font-medium text-gray-600">{{ $registration->status }}</td>
                            <td class="border border-gray-300 px-4 py-2 flex justify-center space-x-2">
                                <!-- View Application -->
                                <a href="{{ route('supervisor.show', $registration->id) }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                    View Application
                                </a>

                                <!-- Verify Documents -->
                                <form action="{{ route('registrations.verify', $registration->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                        Verify Documents
                                    </button>
                                </form>

                                <!-- Download Application PDF (if available) -->
                                @if (Storage::disk('public')->exists('pdfs/registration_' . $registration->id . '.pdf'))
                                    <a href="{{ route('registrations.download-pdf', $registration->id) }}" 
                                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                        Download PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
