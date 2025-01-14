@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Registration Details</h1>

    <!-- Company Information -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Company Information</h2>
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Address:</strong> {{ $registration->address }}</p>
        <p><strong>Mobile:</strong> {{ $registration->mobile }}</p>
        <p><strong>Email:</strong> {{ $registration->email }}</p>
    </div>

    <!-- Directors/Partners -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Directors/Partners</h2>
        @if ($registration->directorsPartners->isNotEmpty())
            <ul class="list-disc pl-6">
                @foreach ($registration->directorsPartners as $director)
                    <li>
                        <strong>{{ $director->name }}</strong> ({{ $director->relation }}) <br>
                        <strong>CNIC:</strong> {{ $director->cnic_number }} <br>
                        <strong>Gender:</strong> {{ ucfirst($director->gender) }} <br>
                        <strong>Date of Birth:</strong> {{ $director->date_of_birth }} <br>
                        <strong>Home Address:</strong> {{ $director->home_address }} <br>
                        <strong>Phone:</strong> {{ $director->phone ?? 'N/A' }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>No directors/partners available.</p>
        @endif
    </div>

    <!-- Uploaded Documents -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Uploaded Documents</h2>
        @if ($registration->documents->isNotEmpty())
            <ul class="list-disc pl-6">
                @foreach ($registration->documents as $document)
                    <li>
                        <strong>{{ $document->document_type }}:</strong> 
                        <a href="{{ asset('storage/' . $document->document_path) }}" target="_blank" class="text-blue-500 underline">
                            View
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No documents uploaded.</p>
        @endif
    </div>

    <!-- Approval/Rejection Actions -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Admin Actions</h2>
        <div class="flex space-x-4">
            <!-- Approve Form -->
            <form action="{{ route('admin.approve', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white font-semibold rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                    Approve
                </button>
            </form>

            <!-- Reject Form -->
            <form action="{{ route('admin.reject', $registration->id) }}" method="POST" class="flex flex-col">
                @csrf
                <textarea 
                    name="rejection_reason" 
                    placeholder="Reason for rejection" 
                    class="w-full px-3 py-2 mb-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-300"
                    required>
                </textarea>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Reject
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
