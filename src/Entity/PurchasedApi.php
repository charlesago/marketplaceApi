<?php

namespace App\Entity;

use App\Repository\PurchasedApiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchasedApiRepository::class)]
class PurchasedApi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedApis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $linkToProfile = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedApis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CreatedApi $linkApi = null;

    #[ORM\Column]
    private ?bool $isApiKeyGenerated = false;

    #[ORM\Column]
    private ?int $remainingRequests = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkToProfile(): ?Profile
    {
        return $this->linkToProfile;
    }

    public function setLinkToProfile(?Profile $linkToProfile): static
    {
        $this->linkToProfile = $linkToProfile;

        return $this;
    }

    public function getLinkApi(): ?CreatedApi
    {
        return $this->linkApi;
    }

    public function setLinkApi(?CreatedApi $linkApi): static
    {
        $this->linkApi = $linkApi;

        return $this;
    }

    public function isApiKeyGenerated(): ?bool
    {
        return $this->isApiKeyGenerated;
    }

    public function setApiKeyGenerated(bool $isApiKeyGenerated): static
    {
        $this->isApiKeyGenerated = $isApiKeyGenerated;

        return $this;
    }

    public function getRemainingRequests(): ?int
    {
        return $this->remainingRequests;
    }

    public function setRemainingRequests(int $remainingRequests): static
    {
        $this->remainingRequests = $remainingRequests;

        return $this;
    }
}
