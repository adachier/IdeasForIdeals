<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\PostLike;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\PostLikeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(CategoriesRepository $repo, UserRepository $repoU, ArticlesRepository $repoA)
    {
        $categories= $repo->findAll();
        $users=$repoU->findAll();
        $articles=$repoA->findAll();
        return $this->render('main/home.html.twig', [
            'categories' => $categories,
            'users' => $users,
            'articles'=> $articles,
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentions")
     */
    public function mentions(CategoriesRepository $repo){
        $categories= $repo->findAll();

        return $this->render('mentionslegales.html.twig', [
            'categories' => $categories,

        ]);
    }

    /**
     * Permet de liker ou unliker un article
     * 
     * @Route ("/article/{id}/like", name="post_like")
     *
     * @param Articles $articles
     * @param ObjectManager $manager
     * @param PostLikeRepository $likeRepo
     * @return Response
     */
    public function like(Articles $articles, EntityManagerInterface $manager, PostLikeRepository $likeRepo): Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => "Unauthorized"
        ], 403);

        if($articles->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'post' => $articles,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager -> flush();

            return $this->json([
                'code' => 200,
                'message' => 'Signature removed',
                'likes' => $likeRepo->count(['post' => $articles])
            ], 200);
        }

        $like = new PostLike();
        $like-> setPost($articles)
            -> setUser($user);
        
        $manager-> persist($like);
        $manager->flush();

        return $this->json(['code'=>200,
        'message' => 'It worked well',
        'likes' => $likeRepo->count(['post'=>$articles])
        ], 200);
    }

}
