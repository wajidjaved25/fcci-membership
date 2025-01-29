@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center text-3xl font-bold">Membership Supervisor Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-lg font-bold">Pending Membership Applications</h2>
        <table class="w-full mt-4 border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Company Name</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $registration)
                <tr class="text-center">
                    <td class="border border-gray-300 px-4 py-2">{{ $registration->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $registration->company_name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $registration->status }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <form action="{{ route('registrations.verify', $registration->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg">Verify Documents</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
