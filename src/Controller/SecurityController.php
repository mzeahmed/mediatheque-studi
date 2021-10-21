<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\MediathequeMailer;
use App\Security\RegisterFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request             $request
     * @param RegisterFormHandler $formHandler
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(Request $request, RegisterFormHandler $formHandler): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();

        $form = $this->createForm(RegisterType::class, $user, [
            'attr' => [
                'id' => 'register_form',
            ],
        ]);

        $formHandler->store($user, $form, 'app_register', 'app_home', $request);

        return $this->render('security/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{activationToken}", name="app_activation")
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @param                   $activationToken
     * @param MediathequeMailer $mailer
     *
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function activate($activationToken, MediathequeMailer $mailer): RedirectResponse
    {
        if (! $this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_home');
        }

        $em   = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['activationToken' => $activationToken]);

        $user
            ->setIsActivated(true)
            ->setActivationToken(null)
        ;

        $em->flush();

        $mailer->validateConfirmation($user);

        $this->addFlash('success', 'Le resident est bien activé, il va recevoir un mail de confirmation');

        return $this->redirectToRoute('app_home');
    }
}
