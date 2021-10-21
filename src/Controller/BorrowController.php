<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrow;
use App\Service\MediathequeMailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("borrow")
 */
class BorrowController extends AbstractController
{
    /**
     * @Route("/reserve/{bookId}", name="app_borrow_new", methods={"GET","POST"})
     * @ParamConverter("book", options={"mapping": {"bookId": "id"}})
     *
     * @param Book $book
     *
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function reserve(Book $book, MediathequeMailer $mailer): Response
    {
        $borrow = new Borrow();
        $em     = $this->getDoctrine()->getManager();

        if (! $this->getUser()) {
            return $this->json([
                'message' => 'Vous n\'êtes pas autorisé à accedér à cette page',
            ], 403);
        }

        $book->setIsReserved(true);
        $em->persist($book);

        $borrow
            ->setBorrower($this->getUser())
            ->setBook($book)
            ->setIsCreatedAt(new \DateTime())
        ;
        $em->persist($borrow);

        $em->flush();

        $mailer->borrowConfirmationMessage($borrow->getBorrower(), $book);

        return $this->json([
            'code' => 'reserved',
            'message' => 'Votre réservation est bien enregistré',
        ], 200);
    }
}
