<?php

namespace App\Classes;
use Illuminate\Notifications\Messages\MailMessage;
use Mailgun\Mailgun;

trait CustomMail
{
    private $apiKey = '7698832daf4c5bd0dead0bb442e9424e-cac494aa-d8f79161';
    private $endPoint = 'https://api.eu.mailgun.net';
    private $alertFrom = 'alert@fpe-sa.com';

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
        $mg = Mailgun::create($this->apiKey, $this->endPoint);

        $mg->messages()->send('fpe-sa.com', [
            'from'    => $this->alertFrom,
            'to'      => $user->email,
            'subject' => $subject,
            'html'    => view($body)->render()
        ]);
    }
}
