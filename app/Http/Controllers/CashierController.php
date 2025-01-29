<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    /**
     * Show pending registrations that require fee payment.
     */
    public function index()
    {
        if (Auth::user()->role !== 'cashier') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registrations = Registration::where('status', 'fee_due')->get();
        return view('cashier.dashboard', compact('registrations'));
    }

    /**
     * Mark a registration fee as paid.
     */
    public function collectFee($id)
    {
        if (Auth::user()->role !== 'cashier') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration = Registration::findOrFail($id);
        $registration->update(['status' => 'fee_paid']);

        return redirect()->route('cashier.dashboard')->with('success', 'Fee collected successfully.');
    }
}
