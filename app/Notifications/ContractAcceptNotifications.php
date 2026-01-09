<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractAcceptNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $options = [
            'header' => 'أهلا و مرحباً بكم:',
            'body' => 'تم قبول العقد الخاص بك رقم "'.$this->contract->code.'" ، برجاء الدفع لتفعيل العقد.',
            'button_text' => 'عرض العقود',
            'url' => url('investor/'.$this->contract->user_id.'/contracts')
        ];

        return (new MailMessage)
            ->subject("تم قبول العقد {$this->contract->code}")
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
            'message' => 'تم قبول العقد',
            'link' => url('investor/'.$this->contract->user_id.'/contracts')
        ];
    }
}
