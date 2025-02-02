<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\SmsService;

class SecretaryController extends Controller
{
    /**
     * Show registrations that need provisional approval.
     */
    public function index()
    {
        if (Auth::user()->role !== 'dg_secretary') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registrations = Registration::where('status', 'audited')->get();
        return view('secretary.dashboard', compact('registrations'));
    }

    /**
     * Approve provisional membership and send to committee review.
     */
    public function approveProvisionalMembership($id, SmsService $smsService)
{
    Log::info("✅ Secretary Approval Function Called for Registration ID: $id");

    // ✅ Ensure Only Secretary Can Approve
    if (Auth::user()->role !== 'dg_secretary') {
        Log::error("❌ Unauthorized access attempt by User ID: " . Auth::id());
        return redirect()->route('home')->with('error', 'Unauthorized action.');
    }

    // ✅ Ensure Registration Exists
    $registration = Registration::findOrFail($id);
    Log::info("✅ Registration Found: " . json_encode($registration));

    // ✅ Generate Membership Number
    $membershipNumber = $this->generateMembershipNumber($registration);

    Log::info("✅ Generated Membership Number: " . ($membershipNumber ?? 'NULL'));

    if (!$membershipNumber) {
        Log::error("❌ Membership number generation failed.");
        return redirect()->route('secretary.dashboard')->with('error', 'Membership number generation failed.');
    }

    // ✅ Update Registration Status and Assign Membership Number
    $registration->update([
        'status' => 'provisionally_approved',
        'membership_number' => $membershipNumber,
    ]);

    Log::info("✅ Registration Updated Successfully: " . json_encode($registration));

    // ✅ Assign "member" role to the user
    $user = User::find($registration->user_id);

    if (!$user) {
        Log::error("❌ Error: User with ID {$registration->user_id} not found.");
        return redirect()->route('secretary.dashboard')->with('error', 'Associated user not found.');
    }

    // ✅ Ensure the "role" is fillable in User Model
    $user->role = 'member';
    $saved = $user->save();

    if ($saved) {
        Log::info("✅ User Role Updated to 'member' for User ID: {$user->id}");
    } else {
        Log::error("❌ Error: Failed to update role for User ID: {$user->id}");
        return redirect()->route('secretary.dashboard')->with('error', 'Failed to update user role.');
    }

    // ✅ Send SMS confirming provisional approval
    $message = "Dear {$registration->company_name}, your membership is provisionally approved. Membership No: {$registration->membership_number}";
    $smsService->sendSms($registration->mobile, $message);

    return redirect()->route('secretary.dashboard')->with('success', 'Provisional membership approved and membership number assigned.');
}

/**
 * Generate Membership Number Based on Coding Scheme
 */
private function generateMembershipNumber($registration)
{
    Log::info("✅ Generating Membership Number for Registration ID: {$registration->id}");

    // 1️⃣ Define Class (First Digit)
    $classCode = (strtolower($registration->membership_class) === 'corporate') ? '1' : '2';
    Log::info("✅ Class Code: $classCode");

    // 2️⃣ Define Firm Type (Second Digit)
    $firmTypeMap = [
        'Proprietorship' => '1',
        'Partnership' => '2',
        'AOP' => '3',
        'Private Limited' => '4',
        'Public Limited' => '5'
    ];
    $firmTypeCode = $firmTypeMap[$registration->firm_type] ?? '0';

    Log::info("✅ Firm Type Code: $firmTypeCode for Firm Type: {$registration->firm_type}");

    if ($firmTypeCode === '0') {
        Log::error("❌ Firm type not found for Registration ID: {$registration->id}");
        return null;
    }

    // 3️⃣ Find Maximum Serial Number (3rd to 5th Digits)
    $latestMembership = Registration::whereNotNull('membership_number')
        ->where('membership_number', 'like', "$classCode$firmTypeCode%")
        ->orderBy('membership_number', 'desc')
        ->first();

    $serialNumber = ($latestMembership) 
        ? (intval(substr($latestMembership->membership_number, 2, 3)) + 1) 
        : 1;
    
    // Ensure Serial Number is 3 Digits (e.g., "001", "012", "123")
    $serialNumber = str_pad($serialNumber, 3, '0', STR_PAD_LEFT);
    Log::info("✅ Generated Serial Number: $serialNumber");

    // 4️⃣ Find Maximum Last Digits (After Dash '-')
    $maxLastDigits = Registration::whereNotNull('membership_number')
        ->max(DB::raw("CAST(SUBSTRING_INDEX(membership_number, '-', -1) AS UNSIGNED)"));

    $nextLastDigits = ($maxLastDigits) ? $maxLastDigits + 1 : 1;
    Log::info("✅ Generated Last Digits: $nextLastDigits");

    // 5️⃣ Construct Membership Number
    $membershipNumber = "$classCode$firmTypeCode$serialNumber-$nextLastDigits";
    Log::info("✅ Final Membership Number: $membershipNumber");

    return $membershipNumber;
}
public function show($id)
{
    $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);
    
    return view('accounts.show', compact('registration'));
}
}
