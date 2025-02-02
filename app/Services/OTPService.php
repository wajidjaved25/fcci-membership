<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OTPService
{
    public function sendOTP($mobileNumber, $otp)
    {
        $apiUrl = config('services.sms.api_url');
        $apiKey = config('services.sms.api_key');
        $senderId = config('services.sms.sender_id');

        // Log the configuration values for debugging
        logger('SMS API Configuration', [
            'api_url' => $apiUrl,
            'api_key' => $apiKey,
            'sender_id' => $senderId,
        ]);

        if (!$apiUrl || !$apiKey || !$senderId) {
            throw new \Exception('SMS API configuration is incomplete.');
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'recipient' => $mobileNumber,
            'sender_id' => $senderId,
            'message' => "Your OTP is: $otp",
        ]);

        return $response->successful();
    }

    public function generateOTP()
    {
        return rand(100000, 999999);
    }

    private function formatMobileNumber($mobileNumber)
    {
        return $mobileNumber; // Adjust this if formatting is needed
    }
}