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
        $request->validate([
            'mobile_number' => 'required|digits_between:10,15',
        ]);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with the provided mobile number does not exist.',
            ], 404);
        }

        $otp = $this->otpService->generateOTP();
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        if ($this->otpService->sendOTP($user->mobile_number, $otp)) {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent successfully to the provided mobile number.',
                'data' => [
                    'mobile_number' => $request->mobile_number,
                    'expires_in' => '10 minutes',
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unable to send OTP. Please try again later.',
        ], 500);
    }

    /**
     * Verify OTP and log the user in.
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits_between:10,15',
            'otp' => 'required|numeric',
        ]);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with the provided mobile number does not exist.',
            ], 404);
        }

        if (!$user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The OTP has expired. Please request a new one.',
            ], 422);
        }

        if ($user->otp !== $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided OTP is incorrect.',
            ], 422);
        }

        // Log the user in
        Auth::login($user);

        // Clear OTP after successful login
        $user->update(['otp' => null, 'otp_expires_at' => null]);

        // Redirect based on user role with a fallback
        $redirectUrl = (strtolower($user->role) === 'admin') 
            ? route('admin.dashboard') 
            : url('/home');

        $redirectUrl = $redirectUrl ?: url('/');

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'data' => [
                'redirect_url' => $redirectUrl,
            ],
        ]);
    }
}
