<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MediathequeMailer
{
    private MailerInterface $mailer;

    /** @see config/services.yaml */
    private string $sender;

    public function __construct(MailerInterface $mailer, string $sender)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function residentIsRegistered($employee)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender))
            ->to(new Address($employee))
            ->subject('Un habitant s\'est inscrit')
            ->htmlTemplate('emails/resident_register.html.twig')
            ->context([
                'employee' => $employee,
            ]);

        $this->mailer->send($email);
    }
}