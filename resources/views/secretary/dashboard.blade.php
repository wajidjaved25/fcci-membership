@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">DG Secretary Dashboard</h1>

    <div class="text-center mb-4">
        <p class="text-lg text-gray-600">Welcome, {{ Auth::user()->name }}</p>
    </div>

    @if($registrations->isEmpty())
        <div class="text-center p-6 bg-white rounded-lg shadow">
            <p class="text-lg text-gray-600">No applications pending for final approval.</p>
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership No.</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($registrations as $registration)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $registration->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $registration->company_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($registration->status) }}</td>
                        
                        <td class="px-6 py-4 text-sm text-gray-500">
                         @if(strtolower($registration->payment_status) === 'paid')
                        <span class="text-green-600 font-bold">Paid</span> 
                        (Rs. {{ number_format($registration->fee_amount) }})
                        @else
                        <span class="text-red-600 font-bold">Pending</span>
                        @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $registration->membership_number ?? 'Not Assigned' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('secretary.show', $registration->id) }}" 
                               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                View Application
                            </a>
                            <a href="{{ route('registrations.download-pdf', $registration->id) }}" 
   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
    Print PDF
</a>

                            <form action="{{ route('secretary.approve-provisional', $registration->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Approve Provisional Membership
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
@endsection
