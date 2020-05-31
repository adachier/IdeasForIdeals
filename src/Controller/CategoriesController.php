<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories/{id}", name="categories")
     */
    public function indexCat(ArticlesRepository $articles, CategoriesRepository $repoC, Categories $cat)
    {
        $categories= $repoC->findAll();
        return $this->render('categories/index.html.twig', [
            'cat' => $cat,
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/article/{id}", name="articles")
     */
    public function article(Articles $articles, CategoriesRepository $repo)
    {
        $categories= $repo->findAll();
        return $this->render('categories/article.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
}
