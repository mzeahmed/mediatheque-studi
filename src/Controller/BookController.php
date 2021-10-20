<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/catalog", name="app_catalog", methods={"GET"})
     *
     * @param BookRepository $bookRepository
     *
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/book/new", name="app_book_new", methods= {"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        if (! $this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_catalog');
        }

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a bien été ajouté au catalogue');

            return $this->redirectToRoute('app_catalog', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
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

    /**
     * @Route("/book/{slug}/edit", name="app_book_edit", methods={"GET", "POST"})
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

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/book/{id}/delete", name="book_delete", methods={"POST"})
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

        return $this->redirectToRoute('app_catalog', [], Response::HTTP_SEE_OTHER);
    }
}
