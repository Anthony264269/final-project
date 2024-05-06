<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;


    #[ORM\OneToOne(mappedBy: 'imageUrl', cascade: ['persist', 'remove'])]
    private ?Vehicule $vehicule = null;

    #[ORM\ManyToOne(inversedBy: 'file')]
    private ?Forum $forum = null;

    #[ORM\OneToOne(mappedBy: 'file', cascade: ['persist', 'remove'])]
    private ?Comment $comment = null;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        // unset the owning side of the relation if necessary
        if ($vehicule === null && $this->vehicule !== null) {
            $this->vehicule->setImageUrl(null);
        }

        // set the owning side of the relation if necessary
        if ($vehicule !== null && $vehicule->getImageUrl() !== $this) {
            $vehicule->setImageUrl($this);
        }

        $this->vehicule = $vehicule;

        return $this;
    }

    public function getForum(): ?Forum
    {
        return $this->forum;
    }

    public function setForum(?Forum $forum): static
    {
        $this->forum = $forum;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        // unset the owning side of the relation if necessary
        if ($comment === null && $this->comment !== null) {
            $this->comment->setFile(null);
        }

        // set the owning side of the relation if necessary
        if ($comment !== null && $comment->getFile() !== $this) {
            $comment->setFile($this);
        }

        $this->comment = $comment;

        return $this;
    }



}
