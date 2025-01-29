<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\MembershipStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);

        // Check if a user already exists for this registration
        if ($registration->user_id) {
            return redirect()->route('admin.dashboard')->with('error', 'User already exists for this registration.');
        }

        // Create a new user
        $user = User::create([
            'name' => $registration->company_name, // Use company name or another appropriate field
            'email' => $registration->email ?? 'no-email-' . $registration->id . '@example.com', // Handle cases without email
            'mobile_number' => $registration->mobile,
            'role' => 'member', // Assign the appropriate role
            'password' => bcrypt(Str::random(8)), // Generate a random password
        ]);

        // Update the registration with the new user ID and status
        $registration->update([
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        // Notify the user via email/SMS
        $user->notify(new MembershipStatusNotification('approved'));

        return redirect()->route('admin.dashboard')->with('success', 'Registration approved successfully.');
    }

    /**
     * Reject a registration.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        // Notify the user via email/SMS
        $registration->user->notify(new MembershipStatusNotification('rejected', $request->input('rejection_reason')));

        return redirect()->route('admin.dashboard')->with('success', 'Registration rejected successfully.');
    }
public function downloadPDF($id)
{
    $registration = Registration::findOrFail($id);
    $pdfPath = 'pdfs/registration_' . $registration->id . '.pdf';

    if (Storage::disk('public')->exists($pdfPath)) {
        return response()->download(storage_path('app/public/' . $pdfPath));
    }

    return redirect()->back()->with('error', 'PDF not found.');
}
}
