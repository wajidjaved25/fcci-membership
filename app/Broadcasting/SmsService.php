<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Services\SMSService;

class SMSChannel
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        // Retrieve the SMS message
        $message = $notification->toSms($notifiable);

        // Send the SMS using the SMSService
        $this->smsService->send($notifiable->mobile, $message);
    }
}
