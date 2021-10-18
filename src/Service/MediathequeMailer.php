<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MediathequeMailer
{
    private Mailer $mailer;
    private string $sender;

    public function __construct(Mailer $mailer, string $sender)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    public function residentIsRegistered()
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender));
    }
}