@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Application Details</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Company Information</h2>
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Membership Number:</strong> {{ $registration->membership_number ?? 'Not Assigned' }}</p>
        
        <h2 class="text-xl font-bold text-gray-700 mt-6 mb-4">Uploaded Documents</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($registration->documents as $document)
                <p><strong>{{ $document->document_type }}:</strong> 
                    <a href="{{ asset('storage/' . $document->document_path) }}" target="_blank">
                        View Document
                    </a>
                </p>
            @endforeach
        </div>

        <div class="mt-6 text-center">
            <form action="{{ route('chairman.approve', $registration->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Approve Membership
                </button>
            </form>

            <form action="{{ route('chairman.reject', $registration->id) }}" method="POST" class="inline-block ml-2">
                @csrf
                <input type="text" name="rejection_reason" placeholder="Rejection reason" required 
                       class="border p-2 rounded">
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Reject Membership
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
