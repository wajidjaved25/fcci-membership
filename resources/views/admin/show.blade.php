@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Registration Details</h1>

        <!-- Company Information -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Company Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div>
                <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
                <p><strong>Address:</strong> {{ $registration->address }}</p>
                <p><strong>Mobile:</strong> {{ $registration->mobile }}</p>
                <p><strong>Email:</strong> {{ $registration->email }}</p>
            </div>
            <div>
                <p><strong>Telephone:</strong> {{ $registration->telephone ?? 'N/A' }}</p>
                <p><strong>Website:</strong> <a href="{{ $registration->website }}" target="_blank" class="text-blue-500 hover:underline">{{ $registration->website ?? 'N/A' }}</a></p>
                <p><strong>Membership Class:</strong> {{ $registration->membership_class }}</p>
                <p><strong>Year of Establishment:</strong> {{ $registration->year_establishment }}</p>
            </div>
        </div>

        <!-- Directors/Partners -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Directors/Partners</h2>
        @if($registration->directorsPartners->isEmpty())
            <p>No directors or partners added.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                @foreach ($registration->directorsPartners as $director)
                    <div class="bg-gray-100 p-4 rounded-lg shadow">
                        <p><strong>Name:</strong> {{ $director->name }}</p>
                        <p><strong>Relation:</strong> {{ $director->relation }}</p>
                        <p><strong>CNIC:</strong> {{ $director->cnic_number }}</p>
                        <p><strong>Date of Birth:</strong> {{ $director->date_of_birth }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($director->gender) }}</p>
                        <p><strong>Home Address:</strong> {{ $director->home_address }}</p>
                        <p><strong>Phone:</strong> {{ $director->phone ?? 'N/A' }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Uploaded Documents -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Uploaded Documents</h2>
        @if($registration->documents->isEmpty())
            <p>No documents uploaded.</p>
        @else
            <ul class="list-disc pl-6 mb-6">
                @foreach ($registration->documents as $document)
                    <li>
                        <strong>{{ $document->document_type }}:</strong> 
                        <a href="{{ asset('storage/' . $document->document_path) }}" target="_blank" class="text-blue-500 hover:underline">View Document</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Role-Based Actions -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Actions</h2>
        <div class="space-y-4">
            <!-- Membership Supervisor: Verify Documents -->
            @if($registration->status === 'pending' && Auth::user()->role === 'membership_supervisor')
            <form action="{{ route('registrations.verify', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white font-bold rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Verify Documents</button>
            </form>
            @endif

            <!-- Cashier: Collect Fee -->
            @if($registration->status === 'fee_due' && Auth::user()->role === 'cashier')
            <form action="{{ route('registrations.fee', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Collect Fee</button>
            </form>
            @endif

            <!-- Accounts/Audit: Audit Documents -->
            @if($registration->status === 'fee_paid' && Auth::user()->role === 'accounts_audit')
            <form action="{{ route('registrations.audit', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white font-bold rounded-lg shadow hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">Audit Documents</button>
            </form>
            @endif

            <!-- DG/Secretary: Approve Provisional Membership -->
            @if($registration->status === 'provisionally_approved' && Auth::user()->role === 'dg_secretary')
            <form action="{{ route('registrations.provisional', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-teal-500 text-white font-bold rounded-lg shadow hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500">Approve Provisional Membership</button>
            </form>
            @endif

            <!-- Chairman/President: Grant Final Approval -->
            @if($registration->status === 'committee_review' && Auth::user()->role === 'chairman_president')
            <form action="{{ route('registrations.final', $registration->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-purple-500 text-white font-bold rounded-lg shadow hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500">Grant Final Approval</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
