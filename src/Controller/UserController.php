<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/my-borrows", name="app_user_borrows")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', []);
    }
}
