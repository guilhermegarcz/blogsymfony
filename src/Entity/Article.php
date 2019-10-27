<?php

namespace App\Entity;

use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\Length(min=10)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $thumbnail;

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="article")
     * @ORM\JoinTable(name="articleTags",
     *     joinColumns={
     *       @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *       @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *      }
     * )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

}
