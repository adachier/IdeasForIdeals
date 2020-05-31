<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="articles_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategoriesRepository $repo): Response
    {
        $categories=$repo->findAll();
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Created at
            $now= New \DateTime('now', new DateTimeZone('Europe/Paris'));
            $article->setCreatedAt($now);

            // Auteur
            $author = $this->getUser();
            $article->setUsers($author);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);

            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'categories'=>$categories,
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET"})
     */
    public function show(Articles $article, CategoriesRepository $repo): Response
    {
        $categories=$repo->findAll();
        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'categories'=>$categories,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article, CategoriesRepository $repo): Response
    {
        $categories=$repo->findAll();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now= New \DateTime('now', new DateTimeZone('Europe/Paris'));
            $article->setUpdatedAt($now);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'categories'=>$categories,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile');
    }
}
