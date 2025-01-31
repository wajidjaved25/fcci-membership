@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-center text-3xl font-bold text-gray-800">Application Details</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <!-- Company Information -->
        <h2 class="text-xl font-bold text-gray-700 mb-4">Company Information</h2>
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Address:</strong> {{ $registration->address }}</p>
        <p><strong>Mobile:</strong> {{ $registration->mobile }}</p>
        <p><strong>Email:</strong> {{ $registration->email }}</p>
        <p><strong>Website:</strong> {{ $registration->website }}</p>
        <p><strong>Membership Class:</strong> {{ $registration->membership_class }}</p>
        <p><strong>Year of Establishment:</strong> {{ $registration->year_establishment }}</p>
        <p><strong>NTN:</strong> {{ $registration->ntn }}</p>
        <p><strong>Sales Tax Number:</strong> {{ $registration->sales_tax_number }}</p>
        <p><strong>Main Business:</strong> {{ $registration->main_business }}</p>
        <p><strong>Product Line:</strong> {{ $registration->product_line }}</p>
        <p><strong>Testimonial 1:</strong> {{ $registration->testimonial_1 }}</p>
        <p><strong>Testimonial 2:</strong> {{ $registration->testimonial_2 }}</p>

        <!-- Directors/Partners Information -->
        <h2 class="text-xl font-bold text-gray-700 mt-6 mb-4">Proprietor/Directors/Partners Details</h2>
        @foreach($registration->directorsPartners as $director)
            <div class="bg-gray-100 p-4 rounded-lg mb-4 shadow-sm">
                <p><strong>Name:</strong> {{ $director->name }}</p>
                <p><strong>CNIC:</strong> {{ $director->cnic_number }}</p>
                <p><strong>Relation:</strong> {{ $director->relation }}</p>
                <p><strong>Date of Birth:</strong> {{ $director->date_of_birth }}</p>
                <p><strong>CNIC Issue Date:</strong> {{ $director->cnic_issue_date }}</p>
                <p><strong>CNIC Expiry Date:</strong> {{ $director->cnic_expiry_date }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($director->gender) }}</p>
                <p><strong>Home Address:</strong> {{ $director->home_address }}</p>
                <p><strong>Phone:</strong> {{ $director->phone }}</p>
                
                <!-- CNIC Document Links -->
                <div class="mt-2">
                    <p><strong>CNIC Front:</strong> 
                        <a href="{{ route('documents.view', ['filename' => basename($director->cnic_front)]) }}" 
                           target="_blank" class="text-blue-500 underline">View CNIC Front</a>
                    </p>
                    <p><strong>CNIC Back:</strong> 
                        <a href="{{ route('documents.view', ['filename' => basename($director->cnic_back)]) }}" 
                           target="_blank" class="text-blue-500 underline">View CNIC Back</a>
                    </p>
                </div>
            </div>
        @endforeach

        <!-- Uploaded Documents -->
        <h2 class="text-xl font-bold text-gray-700 mt-6 mb-4">Uploaded Documents</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($registration->documents as $document)
                <p><strong>{{ $document->document_type }}:</strong> 
                    <a href="{{ route('documents.view', ['filename' => basename($document->document_path)]) }}" 
                       class="text-blue-500 underline" target="_blank">
                       View Document
                    </a>
                </p>
            @endforeach
        </div>

        <!-- Print Application PDF -->
        <div class="mt-6 text-center">
            <a href="{{ route('registration.download', $registration->id) }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Print Application PDF
            </a>
        </div>
    </div>
</div>
@endsection
