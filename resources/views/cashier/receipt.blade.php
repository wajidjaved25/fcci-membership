@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-center text-2xl font-bold text-gray-800">Payment Receipt</h2>
        
        <div class="mt-4">
            <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
            <p><strong>Fee Amount:</strong> Rs. {{ number_format($registration->fee_amount, 2) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($registration->payment_status) }}</p>
            <p><strong>Payment Date:</strong> {{ $registration->fee_paid_at }}</p>
        </div>

        <div class="text-center mt-6">
            <button onclick="printReceipt()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Print Receipt
            </button>
        </div>
    </div>
</div>

<script>
    function printReceipt() {
        window.print();
    }

    // ✅ Auto-open print dialog when page loads
    window.onload = function() {
        printReceipt();
    }
</script>

@endsection
