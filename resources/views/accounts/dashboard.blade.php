@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-center text-3xl font-bold text-gray-800">Accounts Audit Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Applications Awaiting Audit</h2>

        @if($registrations->isEmpty())
            <p class="text-center text-gray-600">No applications pending for audit.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Company Name</th>
                            <th class="border border-gray-300 px-4 py-2">Status</th>
                            <th class="border border-gray-300 px-4 py-2">Fee Paid</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2">{{ $registration->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $registration->company_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ ucfirst($registration->status) }}</td>
                            <td class="border border-gray-300 px-4 py-2">Rs. {{ number_format($registration->fee_paid, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="{{ route('accounts.show', $registration->id) }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    View Application
                                </a>
                                <form action="{{ route('registrations.audit', $registration->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                        Approve Audit
                                    </button>
                                </form>
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
