<?php

namespace App\Entity;

use App\Repository\RequestAccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestAccountRepository::class)
 */
class RequestAccount
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
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idCard;

    /**
     * @ORM\ManyToOne(targetEntity=Banker::class, inversedBy="AccountRequest")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Banker;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="AccountRequest")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setClient(?User $client): self
    {
        $this->Client = $client;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getIdCard(): ?string
    {
        return $this->idCard;
    }

    public function setIdCard(string $idCard): self
    {
        $this->idCard = $idCard;

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
}
