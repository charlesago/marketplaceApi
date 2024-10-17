<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ofUser = null;

    /**
     * @var Collection<int, PurchasedApi>
     */
    #[ORM\OneToMany(targetEntity: PurchasedApi::class, mappedBy: 'linkToProfile')]
    private Collection $purchasedApis;

    /**
     * @var Collection<int, CreatedApi>
     */
    #[ORM\OneToMany(targetEntity: CreatedApi::class, mappedBy: 'creator')]
    private Collection $createdApis;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'ofProfile', orphanRemoval: true)]
    private Collection $orders;

    public function __construct()
    {
        $this->purchasedApis = new ArrayCollection();
        $this->createdApis = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(User $ofUser): static
    {
        $this->ofUser = $ofUser;

        return $this;
    }

    /**
     * @return Collection<int, PurchasedApi>
     */
    public function getPurchasedApis(): Collection
    {
        return $this->purchasedApis;
    }

    public function addPurchasedApi(PurchasedApi $purchasedApi): static
    {
        if (!$this->purchasedApis->contains($purchasedApi)) {
            $this->purchasedApis->add($purchasedApi);
            $purchasedApi->setLinkToProfile($this);
        }

        return $this;
    }

    public function removePurchasedApi(PurchasedApi $purchasedApi): static
    {
        if ($this->purchasedApis->removeElement($purchasedApi)) {
            // set the owning side to null (unless already changed)
            if ($purchasedApi->getLinkToProfile() === $this) {
                $purchasedApi->setLinkToProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CreatedApi>
     */
    public function getCreatedApis(): Collection
    {
        return $this->createdApis;
    }

    public function addCreatedApi(CreatedApi $createdApi): static
    {
        if (!$this->createdApis->contains($createdApi)) {
            $this->createdApis->add($createdApi);
            $createdApi->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedApi(CreatedApi $createdApi): static
    {
        if ($this->createdApis->removeElement($createdApi)) {
            // set the owning side to null (unless already changed)
            if ($createdApi->getCreator() === $this) {
                $createdApi->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setOfProfile($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getOfProfile() === $this) {
                $order->setOfProfile(null);
            }
        }

        return $this;
    }
}
