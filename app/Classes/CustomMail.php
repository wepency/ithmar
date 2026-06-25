<?php

namespace App\Classes;
use Illuminate\Notifications\Messages\MailMessage;
use Mailgun\Mailgun;

trait CustomMail
{
    private function CustomMail($user, $subject, $body){
        if (!is_null($user->email_verified_at)){
            if (preg_match('/(.*)@(live|hotmail)\.(.*)/', $user->email) != false) {
                $this->MailGun($user, $subject, $body);
//                return (new MailMessage);
            }else{
                return $this->LocalMail($subject, $body);
            }
        }
    }

    private function LocalMail($subject, $body){
        return (new MailMessage)
            ->subject($subject)
            ->markdown($body);
    }

    private function MailGun($user, $subject, $body){
        $mg = Mailgun::create(config('services.mailgun.api_key'), config('services.mailgun.endpoint'));

        $mg->messages()->send(config('services.mailgun.domain'), [
            'from'    => config('services.mailgun.from'),
            'to'      => $user->email,
            'subject' => $subject,
            'html'    => view($body)->render()
        ]);
    }
}
