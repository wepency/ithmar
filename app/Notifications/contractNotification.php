<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class contractNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $contract;

    public function __construct($contract)
    {
        $this->contract = $contract;
    }

    public function via($notifiable)
    {
        return ['database'];
    }


    public function toMail($notifiable)
    {
        $options = [
            'header' => 'أهلا و مرحباً بكم:',
            'body' => 'يوجد طلب تصديق عقد جديد في انتظار المراجعة.',
            'button_text' => 'عرض طلبات العقود',
            'url' => admin_url('contracts/requests')
        ];

        return (new MailMessage)
            ->subject('طلب تصديق عقد')
            ->markdown('mail.contract.request', $options);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notifiable' => $notifiable,
            'contract_id' => $this->contract->id,
            'message' => 'هناك طلب تصديق عقد جديد',
            'link' => admin_url('contract/show/'.$this->contract->code)
        ];
    }
}
