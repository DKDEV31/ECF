<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="Accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;

    /**
     * @ORM\OneToMany(targetEntity=Benefit::class, mappedBy="Account")
     */
    private $benefits;

    /**
     * @ORM\OneToMany(targetEntity=RequestBenefit::class, mappedBy="Account")
     */
    private $requestBenefits;

    public function __construct()
    {
        $this->benefits = new ArrayCollection();
        $this->requestBenefits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    /**
     * @return Collection|Benefit[]
     */
    public function getBenefits(): Collection
    {
        return $this->benefits;
    }

    public function addBenefit(Benefit $benefit): self
    {
        if (!$this->benefits->contains($benefit)) {
            $this->benefits[] = $benefit;
            $benefit->setAccount($this);
        }

        return $this;
    }

    public function removeBenefit(Benefit $benefit): self
    {
        if ($this->benefits->removeElement($benefit)) {
            // set the owning side to null (unless already changed)
            if ($benefit->getAccount() === $this) {
                $benefit->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RequestBenefit[]
     */
    public function getRequestBenefits(): Collection
    {
        return $this->requestBenefits;
    }

    public function addRequestBenefit(RequestBenefit $requestBenefit): self
    {
        if (!$this->requestBenefits->contains($requestBenefit)) {
            $this->requestBenefits[] = $requestBenefit;
            $requestBenefit->setAccount($this);
        }

        return $this;
    }

    public function removeRequestBenefit(RequestBenefit $requestBenefit): self
    {
        if ($this->requestBenefits->removeElement($requestBenefit)) {
            // set the owning side to null (unless already changed)
            if ($requestBenefit->getAccount() === $this) {
                $requestBenefit->setAccount(null);
            }
        }

        return $this;
    }
}
