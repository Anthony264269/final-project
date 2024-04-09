<?php

namespace App\Entity;

use App\Repository\SubscriberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $birthAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registratedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Forum::class, mappedBy: 'subscriber')]
    private Collection $forum;

    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'subscriber')]
    private Collection $file;

    


 

    public function __construct()
    {
        $this->forum = new ArrayCollection();
        $this->file = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthAt(): ?\DateTimeImmutable
    {
        return $this->birthAt;
    }

    public function setBirthAt(?\DateTimeImmutable $birthAt): static
    {
        $this->birthAt = $birthAt;

        return $this;
    }

    public function getRegistratedAt(): ?\DateTimeImmutable
    {
        return $this->registratedAt;
    }

    public function setRegistratedAt(\DateTimeImmutable $registratedAt): static
    {
        $this->registratedAt = $registratedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Forum>
     */
    public function getForum(): Collection
    {
        return $this->forum;
    }

    public function addForum(Forum $forum): static
    {
        if (!$this->forum->contains($forum)) {
            $this->forum->add($forum);
            $forum->setSubscriber($this);
        }

        return $this;
    }

    public function removeForum(Forum $forum): static
    {
        if ($this->forum->removeElement($forum)) {
            // set the owning side to null (unless already changed)
            if ($forum->getSubscriber() === $this) {
                $forum->setSubscriber(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(File $file): static
    {
        if (!$this->file->contains($file)) {
            $this->file->add($file);
            $file->setSubscriber($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->file->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getSubscriber() === $this) {
                $file->setSubscriber(null);
            }
        }

        return $this;
    }

 


    
}
