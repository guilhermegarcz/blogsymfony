<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Form\TagType;
use App\Service\FileUploader;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN') and is_granted('oauth2')" )
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="admin_index")
     */
    public function index()
    {
        $repositoryArticle = $this->getDoctrine()->getRepository(Article::class);
        $repositoryTag = $this->getDoctrine()->getRepository(Tag::class);

        $tags = $repositoryTag->findBy([], ['id' => 'DESC']);
        $articles = $repositoryArticle->findBy([], ['id' => 'DESC']);

        return $this->render(
            "admin/index.html.twig",
            [
                'articles' => $articles,
                'tags' => $tags,
            ]
        );
    }

    /**
     * @Route("/article/add", name="article_add")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function addArticle(Request $request, FileUploader $fileUploader)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $article = new Article();
        $article->setDate(new DateTime());
        $article->setUser($user);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $thumbnail */
            $thumbnail = $form['thumbnail']->getData();
            if ($thumbnail) {
                $thumbnailName = $fileUploader->upload($thumbnail);
                $article->setThumbnail($thumbnailName);
            }


            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            "admin/article/add.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     * @param Article $article
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     */
    public function editArticle(Article $article, Request $request, FileUploader $fileUploader)
    {

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $thumbnail */
            $thumbnail = $form['thumbnail']->getData();
            if ($thumbnail) {
                $thumbnailName = $fileUploader->upload($thumbnail);
                $article->setThumbnail($thumbnailName);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("notice", "Article was edited successfully");

            return $this->redirectToRoute("admin_index");
        }

        return $this->render(
            "admin/article/edit.html.twig",
            [
                'article' => $article,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete")
     * @param Article $article
     * @return RedirectResponse
     */
    public function deleteArticle(Article $article)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('notice', 'Article was deleted successfully');

        return $this->redirectToRoute("admin_index");
    }

    /**
     * @Route("/tag/add", name="tag_add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addTag(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash("notice", "Tag added successfully");

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            "admin/tag/add.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/tag/edit/{id}", name="tag_edit")
     * @param Tag $tag
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editTag(Tag $tag, Request $request)
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("notice", "Tag was edited successfully");

            return $this->redirectToRoute('admin_index');
        }

        return $this->render(
            "admin/tag/edit.html.twig",
            [
                'tag' => $tag,
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/tag/delete/{id}", name="tag_delete")
     * @param Tag $tag
     * @return RedirectResponse
     */
    public function deleteTag(Tag $tag)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($tag);
        $manager->flush();

        $this->addFlash("notice", "Tag was deleted successfully");

        return $this->redirectToRoute("admin_index");
    }

}