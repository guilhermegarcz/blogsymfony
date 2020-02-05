<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/", name="article_index")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findBy([], ['id' => 'DESC']);

        return $this->render("article/index.html.twig", ['articles' => $articles]);

    }

    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show(Article $article)
    {
        return $this->render("article/show.html.twig", ['article' => $article]);
    }

    /**
     * @Route("/user/@{username}", name="article_user")
     */
    public function userArticles(User $userWithArticles)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findBy(
            [
                'user' => $userWithArticles,
            ],
            [
                'idc' => 'DESC',
            ]
        );

        return $this->render("article/index.html.twig", ['articles' => $articles]);

    }
}