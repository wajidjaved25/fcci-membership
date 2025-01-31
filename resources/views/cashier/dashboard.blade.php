@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Cashier Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Pending Fee Collections</h2>

        @if($registrations->isEmpty())
            <p class="text-center text-gray-600">No pending payments at the moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Company Name</th>
                            <th class="border border-gray-300 px-4 py-2">Fee Amount</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2">{{ $registration->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $registration->company_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">Rs. {{ number_format($registration->fee_amount, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="confirmFeeCollection({{ $registration->id }}, {{ $registration->fee_amount }})" 
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    Collect Fee
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Collect Fee Confirmation Dialog -->
<div id="feeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold text-gray-700">Confirm Fee Collection</h2>
        <p>Enter the collected fee amount:</p>
        <input type="number" id="feeAmountInput" class="w-full px-4 py-2 border rounded-lg my-2" min="1">
        <p id="feeError" class="text-red-500 hidden">Amount does not match the expected fee!</p>
        <div class="flex justify-end mt-4">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded-lg mr-2">Cancel</button>
            <button onclick="submitFeeCollection()" class="px-4 py-2 bg-green-500 text-white rounded-lg">Confirm</button>
        </div>
    </div>
</div>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    let selectedRegistrationId = null;
    let expectedFee = 0;

    function confirmFeeCollection(registrationId, feeAmount) {
        selectedRegistrationId = registrationId;
        expectedFee = parseFloat(feeAmount);
        document.getElementById('feeAmountInput').value = "";
        document.getElementById('feeError').classList.add('hidden');
        document.getElementById('feeModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('feeModal').classList.add('hidden');
        document.getElementById('feeError').classList.add('hidden');
    }

    function submitFeeCollection() {
        let enteredFee = parseFloat(document.getElementById('feeAmountInput').value);

        if (isNaN(enteredFee) || Math.abs(enteredFee - expectedFee) > 0.01) {
            document.getElementById('feeError').textContent = "Amount must match the exact fee: Rs. " + expectedFee.toFixed(2);
            document.getElementById('feeError').classList.remove('hidden');
            return;
        }

        fetch(`{{ route('cashier.collect-fee', '') }}/${selectedRegistrationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ fee_amount: enteredFee })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                alert("Fee collected successfully!");
                window.location.href = data.redirect_url;  // Auto print receipt after success
            } else {
                alert(data.message || 'Error collecting fee');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    }
</script>

@endsection
