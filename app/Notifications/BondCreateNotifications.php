<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BondCreateNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bond)
    {
        $this->bond = $bond;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'body' => 'تم إصدار سند إغلاق جديد برجاء المراجعة.',
            'button_text' => 'عرض سندات الإغلاق',
            'url' => admin_url('bonds')
        ];

        return (new MailMessage)
            ->subject('سند إغلاق جديد')
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
            'contract_id' => $this->bond->id,
            'message' => 'هناك طلب تصديق عقد جديد',
            'link' => admin_url('bonds')
        ];
    }
}
