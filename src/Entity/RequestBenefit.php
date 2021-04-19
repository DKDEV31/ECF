<?php

namespace App\Entity;

use App\Repository\RequestBenefitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestBenefitRepository::class)
 */
class RequestBenefit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Banker::class, inversedBy="requestBenefits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Banker;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $State;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $BankName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AccountNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="requestBenefits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="requestBenefits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBanker(): ?Banker
    {
        return $this->Banker;
    }

    public function setBanker(?Banker $Banker): self
    {
        $this->Banker = $Banker;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->State;
    }

    public function setState(string $State): self
    {
        $this->State = $State;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBankName(): ?string
    {
        return $this->BankName;
    }

    public function setBankName(string $BankName): self
    {
        $this->BankName = $BankName;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->AccountNumber;
    }

    public function setAccountNumber(string $AccountNumber): self
    {
        $this->AccountNumber = $AccountNumber;

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

    public function getAccount(): ?Account
    {
        return $this->Account;
    }

    public function setAccount(?Account $Account): self
    {
        $this->Account = $Account;

        return $this;
    }
}
