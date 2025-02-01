@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Member Dashboard</h1>

    <div class="text-center mb-4">
        <p class="text-lg text-gray-600">Welcome, {{ Auth::user()->name }}</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Membership Information</h2>
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Membership Number:</strong> {{ $registration->membership_number }}</p>
        <p><strong>Membership Status:</strong> {{ ucfirst($registration->status) }}</p>

        <h2 class="text-xl font-bold text-gray-700 mt-6 mb-4">Member Actions</h2>

        <div class="flex justify-center space-x-4">
            <form action="{{ route('member.renew') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Request Membership Renewal
                </button>
            </form>

            <form action="{{ route('member.visa') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Request Visa Letter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
