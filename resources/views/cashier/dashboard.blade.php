@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-center text-3xl font-bold text-gray-800">Cashier Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-lg font-bold text-gray-700 mb-4">Pending Membership Payments</h2>

        @if($registrations->isEmpty())
            <p class="text-center text-gray-600">No pending payments at the moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Company Name</th>
                            <th class="border border-gray-300 px-4 py-2">Membership Class</th>
                            <th class="border border-gray-300 px-4 py-2">Fee Amount</th>
                            <th class="border border-gray-300 px-4 py-2">Status</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                            @if($registration->payment_status === 'pending') 
                            <tr class="text-center">
                                <td class="border border-gray-300 px-4 py-2">{{ $registration->id }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $registration->company_name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $registration->membership_class }}</td>
                                <td class="border border-gray-300 px-4 py-2">Rs. {{ $registration->fee_amount }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ ucfirst($registration->payment_status) }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <form action="{{ route('cashier.collect-fee', $registration->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                            Collect Fee
                                        </button>
                                    </form>
                                    <a href="{{ route('cashier.print-receipt', $registration->id) }}" 
                                       class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        Print Receipt
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
