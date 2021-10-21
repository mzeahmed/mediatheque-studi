<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/catalog/{page<\d+>?1}", name="app_catalog", methods={"GET"})
     *
     * @param Paginator      $paginator
     * @param                $page
     *
     * @return Response
     */
    public function index(Paginator $paginator, $page): Response
    {
        $paginator
            ->setEntityClass(Book::class)
            ->setLimit(6)
            ->setPage($page)
            ->setOrderBy(['isReleasedAt' => 'DESC'])
        ;

        return $this->render('book/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/book/{slug}", name="app_book_show", methods={"GET"})
     *
     * @param Book $book
     *
     * @return Response
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
