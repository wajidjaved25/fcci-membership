<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiUrl;
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.api_url');
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id');
    }

    public function sendSms($mobileNumber, $message)
    {
        Log::info("🔹 Sending SMS to: $mobileNumber | Message: $message");

        if (!$this->apiUrl || !$this->apiKey || !$this->senderId) {
            Log::error('❌ SMS API configuration is missing.');
            return response()->json([
                'status' => 'error',
                'message' => 'SMS API configuration is missing.',
            ], 500);
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'recipient' => $mobileNumber,
                'sender_id' => $this->senderId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("✅ SMS sent successfully to $mobileNumber. Response: " . json_encode($response->json()));
                return response()->json([
                    'status' => 'success',
                    'message' => 'SMS sent successfully.',
                    'data' => [
                        'mobile_number' => $mobileNumber,
                        'message' => $message,
                    ]
                ]);
            } else {
                Log::error("❌ SMS sending failed. Response Code: " . $response->status() . " | Response: " . json_encode($response->json()));
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send SMS.',
                    'data' => [
                        'mobile_number' => $mobileNumber,
                        'response' => $response->json(),
                    ]
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error("❌ SMS Sending Failed: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending SMS.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
