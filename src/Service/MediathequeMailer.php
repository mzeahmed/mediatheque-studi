<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MediathequeMailer
{
    private MailerInterface $mailer;

    /** @see config/services.yaml */
    private string $sender;
    private string $adminEmail;

    public function __construct(MailerInterface $mailer, string $sender, string $adminEmail)
    {
        $this->mailer     = $mailer;
        $this->sender     = $sender;
        $this->adminEmail = $adminEmail;
    }

    /**
     * @param User $employee
     * @param User $resident
     *
     * @throws TransportExceptionInterface
     */
    public function residentIsRegistered(User $employee, User $resident)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender))
            ->to(new Address($this->adminEmail))
            ->subject('Un habitant s\'est inscrit')
            ->htmlTemplate('emails/resident_register.html.twig')
            ->context([
                'employee' => $employee,
                'resident' => $resident,
            ]);

        $this->mailer->send($email);
    }

    /**
     * @param User $resident
     *
     * @throws TransportExceptionInterface
     */
    public function validateConfirmation(User $resident)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender))
            ->to(new Address($resident->getEmail()))
            ->subject('Votre a été validé')
            ->htmlTemplate('emails/resident_validation.html.twig')
            ->context([
                'resident' => $resident,
            ]);

        $this->mailer->send($email);
    }

    /***
     * @param $borrower
     * @param $book
     *
     * @throws TransportExceptionInterface
     */
    public function borrowConfirmationMessage($borrower, $book)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender))
            ->to(new Address($borrower->getEmail()))
            ->subject('Confirmation de reservation')
            ->htmlTemplate('emails/borrowing_confirmation.html.twig')
            ->context([
                'borrower' => $borrower,
                'book' => $book,
            ]);

        $this->mailer->send($email);
    }
}