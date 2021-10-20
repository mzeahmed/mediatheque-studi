<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\Borrow;
use App\Form\BorrowType;
use App\Repository\BorrowRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("borrow")
 */
class BorrowController extends AbstractController
{
    // #[Route('/', name: 'borrow_index', methods: ['GET'])]

    /**
     * @Route("/", name="app_borrow")
     *
     * @param BorrowRepository $borrowRepository
     *
     * @return Response
     */
    public function index(BorrowRepository $borrowRepository): Response
    {
        return $this->render('borrow/index.html.twig', [
            'borrows' => $borrowRepository->findAll(),
        ]);
    }

    /**
     * @Route("/reserve/{bookId}", name="app_borrow_new", methods={"GET","POST"})
     * @ParamConverter("book", options={"mapping": {"bookId": "id"}})
     *
     * @param Book $book
     *
     * @return Response
     */
    public function reserve(Book $book): Response
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

        return $this->json([
            'code' => 'reserved',
            'message' => 'Votre réservation est bien enregistré',
        ], 200);
    }

    /**
     * @Route("/delete/{id}", name="app_delate_borrow", methods={"POST"})
     * @param Request $request
     * @param Borrow  $borrow
     *
     * @return Response
     */
    public function delete(Request $request, Borrow $borrow): Response
    {
        if ($this->isCsrfTokenValid('delete' . $borrow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($borrow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_borrow', [], Response::HTTP_SEE_OTHER);
    }
}
