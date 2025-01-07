<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\SMSService;

class MembershipStatusNotification extends Notification
{
    use Queueable;

    public $status;
    public $reason;
    protected $smsService;

    /**
     * Create a new notification instance.
     *
     * @param string $status
     * @param string|null $reason
     */
    public function __construct($status, $reason = null)
    {
        $this->status = $status;
        $this->reason = $reason;
        $this->smsService = app(SMSService::class); // Inject SMS service
    }

    /**
     * Specify delivery channels for the notification.
     */
    public function via($notifiable)
    {
        return ['sms']; // Custom SMS channel
    }

    /**
     * Send SMS notification.
     *
     * @param mixed $notifiable
     */
    public function toSms($notifiable)
    {
	return [\App\Broadcasting\SMSChannel::class];
        $message = $this->status === 'approved'
            ? 'Your membership application has been approved. Welcome to the chamber!'
            : "Your membership application has been rejected. Reason: {$this->reason}";

        // Use the SMSService to send the message
        $this->smsService->send($notifiable->mobile, $message);
    }
}
