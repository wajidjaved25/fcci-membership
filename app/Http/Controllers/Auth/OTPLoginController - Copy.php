<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OTPService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            $request->validate([
                'mobile_number' => 'required|digits_between:10,15',
            ]);

            $user = User::where('mobile_number', $request->mobile_number)->first();

            if (!$user) {
                Log::warning('OTP Request Failed: No user found for ' . $request->mobile_number);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No user found with the provided mobile number.',
                ], 404);
            }

            $otp = $this->otpService->generateOTP();
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            if ($this->otpService->sendOTP($user->mobile_number, $otp)) {
                Log::info("OTP Sent: OTP {$otp} sent to {$user->mobile_number}");
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent successfully to the provided mobile number.',
                    'data' => [
                        'mobile_number' => $request->mobile_number,
                        'expires_in' => '10 minutes',
                    ],
                ], 200);
            }

            Log::error('OTP Sending Failed: SMS Service did not respond successfully.');
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to send OTP. Please try again later.',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error in OTP Request: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Verify OTP and log the user in.
     */
    public function verifyOTP(Request $request)
    {
        try {
            $request->validate([
                'mobile_number' => 'required|digits_between:10,15',
                'otp' => 'required|numeric',
            ]);

            $user = User::where('mobile_number', $request->mobile_number)->first();

            if (!$user) {
                Log::warning('OTP Verification Failed: No user found for ' . $request->mobile_number);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No user found with the provided mobile number.',
                ], 404);
            }

            if (!$user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
                Log::warning("OTP Expired: OTP expired for {$user->mobile_number}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'The OTP has expired. Please request a new one.',
                ], 422);
            }

            if ($user->otp !== $request->otp) {
                Log::warning("Invalid OTP: Entered OTP {$request->otp} does not match for {$user->mobile_number}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'The provided OTP is incorrect.',
                ], 422);
            }

            // Log the user in
            Auth::login($user);

            // Clear OTP after successful login
            $user->update(['otp' => null, 'otp_expires_at' => null]);

            // Redirect based on role with fallback to the home page
            $redirectUrl = match (strtolower($user->role)) {
                'admin' => route('admin.dashboard'),
                'membership_supervisor' => route('supervisor.dashboard'),
                'cashier' => route('cashier.dashboard'),
                'accounts_audit' => route('accounts.dashboard'),
                'dg_secretary' => route('secretary.dashboard'),
                'chairman_president' => route('chairman.dashboard'),
                default => route('admin.dashboard'), // Fallback instead of 'home'
            };

            Log::info("User Login: {$user->name} (Role: {$user->role}) redirected to {$redirectUrl}");

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => [
                    'redirect_url' => $redirectUrl,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Error in OTP Verification: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
        }
    }
}
