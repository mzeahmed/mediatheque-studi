<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

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
     * @param Request                     $request
     * @param EntityManagerInterface      $em
     * @param UserPasswordHasherInterface $hasher
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email      = $form->get('email')->getData();
            $emailExist = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($emailExist) {
                $this->addFlash('danger',
                    'Désolé, cette email est déja present dans notre base de données, veuillez en choisir un autre'
                );

                return $this->redirectToRoute('app_register');
            }

            $user
                ->setPassword($hasher->hashPassword($user, $form->get('password')->getData()))
                ->setRoles(['ROLE_USER'])
            ;

            $em->persist($user);
            $em->flush();

            // Envoie de l'email à un emplyé/administrateur

            $this->addFlash(
                'success',
                'Votre compte a bien été créé, une validation par un de nos emplyés est neccéssaire, vous recevrez un mail de confirmation'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
