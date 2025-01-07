<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Notifications\MembershipStatusNotification;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a list of pending registrations.
     */
    public function index()
    {
        // Ensure only admins can access this method
        if (Auth::user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Access denied.');
        }

        // Fetch pending registrations
        $registrations = Registration::whereNull('status')->get();

        return view('admin.dashboard', compact('registrations'));
    }

    /**
     * Show details of a specific registration.
     *
     * @param int $id
     */
    public function show($id)
    {
        // Ensure only admins can access this method
        if (Auth::user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Access denied.');
        }

        // Fetch registration details with related models
        $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);

        return view('admin.show', compact('registration'));
    }

    /**
     * Approve a registration.
     *
     * @param Request $request
     * @param int $id
     */
    public function approve(Request $request, $id)
    {
        // Ensure only admins can access this method
        if (Auth::user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Access denied.');
        }

        $registration = Registration::findOrFail($id);
        $registration->status = 'approved';
        $registration->save();

        // Notify the user via email/SMS
        $registration->user->notify(new MembershipStatusNotification('approved'));

        return redirect()->route('admin.dashboard')->with('success', 'Registration approved successfully.');
    }

    /**
     * Reject a registration.
     *
     * @param Request $request
     * @param int $id
     */
    public function reject(Request $request, $id)
    {
        // Ensure only admins can access this method
        if (Auth::user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Access denied.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $registration = Registration::findOrFail($id);
        $registration->status = 'rejected';
        $registration->rejection_reason = $request->input('rejection_reason');
        $registration->save();

        // Notify the user via email/SMS
        $registration->user->notify(new MembershipStatusNotification('rejected', $request->input('rejection_reason')));

        return redirect()->route('admin.dashboard')->with('success', 'Registration rejected successfully.');
    }
}
