<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_fk;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post_fk;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_added;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFk(): ?User
    {
        return $this->user_fk;
    }

    public function setUserFk(?User $user_fk): self
    {
        $this->user_fk = $user_fk;

        return $this;
    }

    public function getPostFk(): ?Post
    {
        return $this->post_fk;
    }

    public function setPostFk(?Post $post_fk): self
    {
        $this->post_fk = $post_fk;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }
	public function __toString()
         	{
         		return  substr($this->text, 0, 20);
         	}
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->date_added;
    }

    public function setDateAdded(\DateTimeInterface $date_added): self
    {
        $this->date_added = $date_added;

        return $this;
    }
}
