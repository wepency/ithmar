<?php

namespace App\Notifications;

use App\Classes\CustomMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractNotifications extends Notification implements ShouldQueue
{
    use Queueable;
    use CustomMail;

    private $contract;

    public function __construct($contract)
    {
        $this->contract = $contract;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return $this->CustomMail($notifiable, 'عقد جديد', 'mail.contract.request');
    }

    public function toArray($notifiable)
    {
        return [
            'notifiable' => $notifiable,
            'contract_id' => $this->contract->id,
            'message' => 'هناك طلب تصديق عقد جديد',
            'link' => admin_url('contracts/requests')
        ];
    }
}
