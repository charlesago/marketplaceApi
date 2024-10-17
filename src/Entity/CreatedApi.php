<?php

namespace App\Entity;

use App\Repository\CreatedApiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreatedApiRepository::class)]
class CreatedApi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $requestAmountPerSale = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $apiKey = null;

    #[ORM\Column(length: 255)]
    private ?string $docLink = null;

    #[ORM\ManyToOne(inversedBy: 'createdApis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $creator = null;

    #[ORM\Column(length: 255)]
    private ?string $linkToApiUser = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'apis')]
    private Collection $orders;

    /**
     * @var Collection<int, PurchasedApi>
     */
    #[ORM\OneToMany(targetEntity: PurchasedApi::class, mappedBy: 'linkApi')]
    private Collection $purchasedApis;

    #[ORM\Column(length: 255)]
    private ?string $linkToApiRequest = null;

    #[ORM\Column(length: 255)]
    private ?string $linkToApiUserDelete = null;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->purchasedApis = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRequestAmountPerSale(): ?int
    {
        return $this->requestAmountPerSale;
    }

    public function setRequestAmountPerSale(int $requestAmountPerSale): static
    {
        $this->requestAmountPerSale = $requestAmountPerSale;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getDocLink(): ?string
    {
        return $this->docLink;
    }

    public function setDocLink(string $docLink): static
    {
        $this->docLink = $docLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreator(): ?Profile
    {
        return $this->creator;
    }

    public function setCreator(?Profile $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getLinkToApiUser(): ?string
    {
        return $this->linkToApiUser;
    }

    public function setLinkToApiUser(string $linkToApiUser): static
    {
        $this->linkToApiUser = $linkToApiUser;

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
            $order->addApi($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            $order->removeApi($this);
        }

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
            $purchasedApi->setLinkApi($this);
        }

        return $this;
    }

    public function removePurchasedApi(PurchasedApi $purchasedApi): static
    {
        if ($this->purchasedApis->removeElement($purchasedApi)) {
            // set the owning side to null (unless already changed)
            if ($purchasedApi->getLinkApi() === $this) {
                $purchasedApi->setLinkApi(null);
            }
        }

        return $this;
    }

    public function getLinkToApiRequest(): ?string
    {
        return $this->linkToApiRequest;
    }

    public function setLinkToApiRequest(string $linkToApiRequest): static
    {
        $this->linkToApiRequest = $linkToApiRequest;

        return $this;
    }

    public function getLinkToApiUserDelete(): ?string
    {
        return $this->linkToApiUserDelete;
    }

    public function setLinkToApiUserDelete(string $linkToApiUserDelete): static
    {
        $this->linkToApiUserDelete = $linkToApiUserDelete;

        return $this;
    }
}
