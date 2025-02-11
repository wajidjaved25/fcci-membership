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
                Log::warning("❌ OTP Request Failed: No user found for {$request->mobile_number}");
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
                Log::info("✅ OTP Sent: OTP {$otp} sent to {$user->mobile_number}");
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent successfully to the provided mobile number.',
                    'data' => [
                        'mobile_number' => $request->mobile_number,
                        'expires_in' => '10 minutes',
                    ],
                ], 200);
            }

            Log::error("❌ OTP Sending Failed: SMS Service did not respond successfully.");
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to send OTP. Please try again later.',
            ], 500);

        } catch (\Exception $e) {
            Log::error("❌ Error in OTP Request: " . $e->getMessage());
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'No user found with the provided mobile number.',
                ], 404);
            }

            // ✅ Fixed OTPs for Demo Users
            $fixedOtpUsers = [
                '920000000001' => '111111', // Admin
                '920000000002' => '222222', // Supervisor
                '920000000003' => '333333', // Cashier
                '920000000004' => '444444', // Accounts
                '920000000005' => '555555', // Secretary
                '920000000006' => '666666', // Chairman
                '920000000007' => '777777', // Member
            ];

            // ✅ Allow login with fixed OTPs for demo users
            if (isset($fixedOtpUsers[$user->mobile_number]) && $request->otp == $fixedOtpUsers[$user->mobile_number]) {
                Auth::login($user);
                $user->update(['otp' => null, 'otp_expires_at' => null]);

                $redirectUrl = $this->getRedirectUrl($user);
                Log::info("✅ Demo User Login: {$user->name} (Role: {$user->role}) redirected to {$redirectUrl}");

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful.',
                    'data' => ['redirect_url' => $redirectUrl],
                ]);
            }

            // ✅ Normal OTP verification
            if (!$user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at) || $user->otp !== $request->otp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired OTP.',
                ], 422);
            }

            // ✅ Login the user
            Auth::login($user);
            $user->update(['otp' => null, 'otp_expires_at' => null]);

            $redirectUrl = $this->getRedirectUrl($user);
            Log::info("✅ User Login: {$user->name} (Role: {$user->role}) redirected to {$redirectUrl}");

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => ['redirect_url' => $redirectUrl],
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Error in OTP Verification: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Get redirect URL based on user role.
     */
    private function getRedirectUrl($user)
    {
        $roleRedirects = [
            'admin' => route('admin.dashboard'),
            'membership_supervisor' => route('supervisor.dashboard'),
            'cashier' => route('cashier.dashboard'),
            'accounts_audit' => route('accounts.dashboard'),
            'dg_secretary' => route('secretary.dashboard'),
            'chairman_president' => route('chairman.dashboard'),
            'member' => route('member.dashboard'),
        ];

        return $roleRedirects[strtolower($user->role)] ?? url('/home');
    }
}
