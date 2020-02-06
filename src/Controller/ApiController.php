<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api")
 */
class ApiController extends AbstractController
{

    /**
     * @Route("/blogs")
     * @return array
     */
    public function getArticles()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findBy([], ['id' => 'DESC']);

        $articleArray = [];

        /**
         * @var Article $article
         */
        foreach ($articles as $id => $article) {
            $articleArray[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
            ];
        }

        return $articleArray;
    }

    /**
     * @Route("/blogs/{id}")
     * @param Article $article
     * @return array
     */
    public function getArticle(Article $article)
    {
        $tags = [];
        /**
         * @var Tag $tag
         */
        foreach ($article->getTags() as $tag) {
            $tags[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }

        return [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'text' => $article->getText(),
            'tags' => $tags,
        ];
    }


}
