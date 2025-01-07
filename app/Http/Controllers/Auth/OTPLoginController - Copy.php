<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OTPService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OTPLoginController extends Controller
{
    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Request OTP for login.
     */
    public function requestOTP(Request $request)
    {
        $request->validate(['mobile_number' => 'required|numeric']);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $otp = $this->otpService->generateOTP();
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        if ($this->otpService->sendOTP($user->mobile_number, $otp)) {
            return response()->json(['message' => 'OTP sent successfully.']);
        }

        return response()->json(['message' => 'Failed to send OTP.'], 500);
    }

    /**
     * Verify OTP and log the user in.
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP has expired.'], 422);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP.'], 422);
        }

        // Log the user in
        Auth::login($user);

        // Clear OTP after successful login
        $user->update(['otp' => null, 'otp_expires_at' => null]);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Login successful.',
                'redirect_url' => route('admin.dashboard'),
            ]);
        }

        return response()->json([
            'message' => 'Login successful.',
            'redirect_url' => url('/home'),
        ]);
    }
}
