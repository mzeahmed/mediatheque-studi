<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Security\RegisterFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     *
     * @param Request             $request
     * @param RegisterFormHandler $formHandler
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, RegisterFormHandler $formHandler): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_catalog');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user, [
            'attr' => [
                'id' => 'register_form',
            ],
        ]);

        $formHandler->store($user, $form, 'app_home', 'app_home', $request);

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
