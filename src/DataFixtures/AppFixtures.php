<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => "user1",
            'password' => "user1234",
            'email' => "user1@mmonks.local",
            'role' => [User::ROLE_USER],
        ],
        [
            'username' => "admin",
            'password' => "admin",
            'email' => "galhetas@puckab.pt",
            'role' => [User::ROLE_ADMIN],
        ],
    ];
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
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadTags($manager);
        $this->loadUsers($manager);
        $this->loadArticles($manager);
    }

    private function loadTags(ObjectManager $manager)
    {
        for ($i = 0; $i < sizeof(self::TAGS); $i++) {
            $tag = new Tag();
            $tag->setName(self::TAGS[$i]);
            $this->setReference("Tag_".$i, $tag);
            $manager->persist($tag);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setEmail($userData['email']);
            $user->setRoles($userData['role']);
            $this->addReference($userData['username'], $user);

            $manager->persist($user);
        }
        $manager->flush();


    }

    private function loadArticles(ObjectManager $manager)
    {
        for ($i = 0; $i < sizeof(self::POSTS); $i++) {
            $article = new Article();
            $article->setUser($this->getReference(self::USERS[1]['username']));
            $article->setTitle(self::POSTS[$i]['title']);
            $article->setText(self::POSTS[$i]['text']);
            for ($z = 0; $z < rand(0, 2); $z++) {
                $article->getTags()->add($this->getReference("Tag_".rand(0, sizeof(self::TAGS) - 1)));
            }
            $date = new DateTime();
            $article->setDate($date);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
