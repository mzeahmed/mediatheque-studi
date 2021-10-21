<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Entity\Borrow;
use App\Service\Paginator;
use App\Repository\BorrowRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 *
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class BookAdminController extends AbstractController
{
    /**
     * @Route("/{page<\d+>?1}", name="app_catalog_admin", methods={"GET"})
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

        return $this->render('admin/book/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/book/new", name="app_book_new_admin", methods= {"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        if (! $this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_home');
        }

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a bien été ajouté au catalogue');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/{slug}", name="app_book_show_admin", methods={"GET"})
     *
     * @param Book $book
     *
     * @return Response
     */
    public function show(Book $book): Response
    {
        return $this->render('admin/book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/book/{slug}/edit", name="app_book_edit_admin", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Book    $book
     *
     * @return Response
     */
    public function edit(Request $request, Book $book): Response
    {
        if (! $this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_catalog');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le livre a été modifié avec succés');

            return $this->redirectToRoute('app_book_show', [
                'slug' => $book->getSlug(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/book/{id}/delete", name="app_book_delete_admin", methods={"POST"})
     *
     * @param Request $request
     * @param Book    $book
     *
     * @return Response
     */
    public function delete(Request $request, Book $book): Response
    {
        if (! $this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_catalog');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_catalog_admin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/borrows", name="app_borrow_admin")
     *
     * @param BorrowRepository $borrowRepository
     *
     * @return Response
     */
    public function showBorrows(BorrowRepository $borrowRepository): Response
    {
        return $this->render('admin/borrow/index.html.twig', [
            'borrows' => $borrowRepository->findAll(),
        ]);
    }

    /**
     * @Route("/delete/borrow/{id}", name="app_borrow_delete_admin", methods={"POST"})
     *
     * @param Request $request
     * @param Borrow  $borrow
     *
     * @return Response
     */
    public function deleteBorrow(Request $request, Borrow $borrow): Response
    {
        $book = $borrow->getBook();

        if ($this->isCsrfTokenValid('delete' . $borrow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($borrow);

            $book->setIsReserved(false);

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_catalog_admin', [], Response::HTTP_SEE_OTHER);
    }
}