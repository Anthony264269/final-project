<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $motorization = null;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    private ?MyGarage $myGarage = null;

    #[ORM\ManyToOne(inversedBy: 'vehicule')]
    private ?Category $category = null;

    #[ORM\OneToMany(targetEntity: Maintenance::class, mappedBy: 'vehicule')]
    private Collection $maintenance;


    

    

  

    public function __construct()
    {
        $this->maintenance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getMotorization(): ?string
    {
        return $this->motorization;
    }

    public function setMotorization(string $motorization): static
    {
        $this->motorization = $motorization;

        return $this;
    }

    public function getMyGarage(): ?MyGarage
    {
        return $this->myGarage;
    }

    public function setMyGarage(?MyGarage $myGarage): static
    {
        $this->myGarage = $myGarage;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenance(): Collection
    {
        return $this->maintenance;
    }

    public function addMaintenance(Maintenance $maintenance): static
    {
        if (!$this->maintenance->contains($maintenance)) {
            $this->maintenance->add($maintenance);
            $maintenance->setVehicule($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): static
    {
        if ($this->maintenance->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getVehicule() === $this) {
                $maintenance->setVehicule(null);
            }
        }

        return $this;
    }





}
