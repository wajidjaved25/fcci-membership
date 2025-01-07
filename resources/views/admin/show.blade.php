@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registration Details</h1>

    <h3>Company Information</h3>
    <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
    <p><strong>Address:</strong> {{ $registration->address }}</p>
    <p><strong>Mobile:</strong> {{ $registration->mobile }}</p>
    <p><strong>Email:</strong> {{ $registration->email }}</p>

    <h3>Directors/Partners</h3>
    @foreach ($registration->directorsPartners as $director)
    <p>{{ $director->name }} ({{ $director->relation }})</p>
    @endforeach

    <h3>Uploaded Documents</h3>
    @foreach ($registration->documents as $document)
    <p>{{ $document->document_type }}: <a href="{{ asset('storage/' . $document->document_path) }}" target="_blank">View</a></p>
    @endforeach

    <form action="{{ route('admin.approve', $registration->id) }}" method="POST" style="display:inline-block;">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
    </form>

    <form action="{{ route('admin.reject', $registration->id) }}" method="POST" style="display:inline-block;">
        @csrf
        <textarea name="rejection_reason" placeholder="Reason for rejection" required></textarea>
        <button type="submit" class="btn btn-danger">Reject</button>
    </form>
</div>
@endsection
