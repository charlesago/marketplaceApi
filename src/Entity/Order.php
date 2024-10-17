<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $ofProfile = null;

    #[ORM\Column]
    private ?float $total = null;

    /**
     * @var Collection<int, CreatedApi>
     */
    #[ORM\ManyToMany(targetEntity: CreatedApi::class, inversedBy: 'orders')]
    private Collection $apis;


    public function __construct()
    {
        $this->apis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfProfile(): ?Profile
    {
        return $this->ofProfile;
    }

    public function setOfProfile(?Profile $ofProfile): static
    {
        $this->ofProfile = $ofProfile;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection<int, CreatedApi>
     */
    public function getApis(): Collection
    {
        return $this->apis;
    }

    public function addApi(CreatedApi $api): static
    {
        if (!$this->apis->contains($api)) {
            $this->apis->add($api);
        }

        return $this;
    }

    public function removeApi(CreatedApi $api): static
    {
        $this->apis->removeElement($api);

        return $this;
    }
}
