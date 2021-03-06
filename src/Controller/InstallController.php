<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\InstallType;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InstallController extends AbstractController
{
    private const TAGS = [
        'Fresh',
        'Updating',
        'Symfony',
    ];
    private const POSTS = [
        [
            'title' => "Don't Count Your Chickens Before They Hatch",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Throw In the Towel",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Drawing a Blank",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Right Off the Bat",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Keep Your Shirt On",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Two Down, One to Go",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Quality Time",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
        [
            'title' => "Top Drawer",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut id elit vel magna viverra elementum",
        ],
    ];


    /**
     * @Route("/install", name="install_index")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function install(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $filesystem = new Filesystem();

        if ($filesystem->exists('install.lock')) {
            return $this->redirectToRoute("article_index");
        }

        $user = new User();
        $form = $this->createForm(InstallType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();


            if ($form['generateArticles']->getData()) {
                $tags = $this->loadTags($entityManager);
                $articles = $this->loadArticles($user, $tags, $entityManager);
            }

            $filesystem->dumpFile('install.lock', uniqid());

            $this->addFlash("notice", "User registered successfully.");

            return $this->redirectToRoute("auth_login");
        }

        return $this->render(
            "install.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    private function loadTags($entityManager)
    {
        $tags = [];
        for ($i = 0; $i < sizeof(self::TAGS); $i++) {
            $tag = new Tag();
            $tag->setName(self::TAGS[$i]);
            $entityManager->persist($tag);
            $tags[] = $tag;
        }
        $entityManager->flush();

        return $tags;
    }

    private function loadArticles($user, $tags, $entityManager)
    {
        for ($i = 0; $i < sizeof(self::POSTS); $i++) {
            $article = new Article();
            $article->setUser($user);
            $article->setTitle(self::POSTS[$i]['title']);
            $article->setText(self::POSTS[$i]['text']);
            for ($z = 0; $z < rand(0, 3); $z++) {
                $article->getTags()->add($tags[$z]);
            }
            $date = new DateTime();
            $article->setDate($date);
            $entityManager->persist($article);
        }
        $entityManager->flush();
    }


}