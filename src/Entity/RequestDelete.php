<?php

namespace App\Entity;

use App\Repository\RequestDeleteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestDeleteRepository::class)
 */
class RequestDelete
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $CloseRequest;

    /**
     * @ORM\ManyToOne(targetEntity=Banker::class, inversedBy="requestDeletes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Banker;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="requestDeletes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountNumber;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCloseRequest(): ?string
    {
        return $this->CloseRequest;
    }

    public function setCloseRequest(string $CloseRequest): self
    {
        $this->CloseRequest = $CloseRequest;

        return $this;
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

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

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

}
