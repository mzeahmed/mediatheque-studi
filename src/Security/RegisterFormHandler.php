<?php

namespace App\Security;

use App\Entity\User;
use App\Service\Employee;
use App\Service\MediathequeMailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterFormHandler extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    private MediathequeMailer $mailer;
    private Employee $employee;

    /**
     * @param UserPasswordHasherInterface $hasher
     * @param MediathequeMailer           $mailer
     * @param Employee                    $employee
     */
    public function __construct(UserPasswordHasherInterface $hasher, MediathequeMailer $mailer, Employee $employee)
    {
        $this->hasher   = $hasher;
        $this->mailer   = $mailer;
        $this->employee = $employee;
    }

    /**
     * @param User     $user
     * @param          $form                    | créer le formulaire directement dans le controller
     * @param string   $errorRedirectionRoute   | route de rediection si un utilisateur éxiste deja, la route courrant generalement
     * @param string   $successRedirectionRoute | route de redirection apres succés
     * @param Request  $request
     *
     * @return RedirectResponse|void
     * @throws TransportExceptionInterface
     */
    public function store(User $user, $form, string $errorRedirectionRoute, string $successRedirectionRoute, Request $request)
    {
        $employeeEmail = $this->employee->getEmployee()->getEmail();
        $em            = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email      = $form->get('email')->getData();
            $emailExist = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($emailExist) {
                $this->addFlash('danger',
                    'Désolé, cette email est déja present dans notre base de données, veuillez en choisir un autre'
                );

                return $this->redirectToRoute($errorRedirectionRoute);
            }

            $user
                ->setPassword($this->hasher->hashPassword($user, $form->get('password')->getData()))
                ->setRoles(['ROLE_USER'])
            ;

            $em->persist($user);
            $em->flush();

            $this->mailer->residentIsRegistered($employeeEmail);

            $this->addFlash(
                'success',
                'Votre compte a bien été créé, une validation par un de nos emplyés est neccéssaire, vous recevrez un mail de confirmation'
            );

            return $this->redirectToRoute($successRedirectionRoute);
        }
    }
}