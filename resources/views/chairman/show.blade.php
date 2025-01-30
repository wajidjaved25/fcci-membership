@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-center text-3xl font-bold text-gray-800">Application Details</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Company Information</h2>
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Address:</strong> {{ $registration->address }}</p>
        <p><strong>Mobile:</strong> {{ $registration->mobile }}</p>
        <p><strong>Email:</strong> {{ $registration->email }}</p>
        <p><strong>Website:</strong> {{ $registration->website }}</p>
        <p><strong>Membership Class:</strong> {{ $registration->membership_class }}</p>
        <p><strong>Year of Establishment:</strong> {{ $registration->year_establishment }}</p>

        <h2 class="text-xl font-bold text-gray-700 mt-6 mb-4">Directors/Partners</h2>
        @foreach($registration->directorsPartners as $director)
            <p><strong>Name:</strong> {{ $director->name }}</p>
            <p><strong>CNIC:</strong> {{ $director->cnic_number }}</p>
            <p><strong>Relation:</strong> {{ $director->relation }}</p>
            <p><strong>Address:</strong> {{ $director->home_address }}</p>
            <hr class="my-4">
        @endforeach

        <h2 class="text-xl font-bold text-gray-700 mt-6 mb-4">Uploaded Documents</h2>
        @foreach($registration->documents as $document)
            <p><strong>{{ $document->document_type }}:</strong> 
                <a href="{{ asset('storage/' . $document->document_path) }}" 
                   target="_blank" class="text-blue-500 underline">
                   View Document
                </a>
            </p>
        @endforeach

        <div class="mt-6 text-center">
            <form action="{{ route('registrations.verify', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Verify Documents
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
