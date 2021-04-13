<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(fields={"email"}, message="Ce mail est déja utilisé")
 */
class Client extends User
{

    /**
     * @ORM\OneToMany(targetEntity=RequestAccount::class, mappedBy="Client")
     */
    private $AccountRequest;

    /**
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="Client")
     */
    private $Accounts;

    /**
     * @ORM\OneToMany(targetEntity=RequestDelete::class, mappedBy="Client")
     */
    private $requestDeletes;

    /**
     * @ORM\OneToMany(targetEntity=RequestBenefit::class, mappedBy="Client")
     */
    private $requestBenefits;

    public function __construct()
    {
        $this->AccountRequest = new ArrayCollection();
        $this->Accounts = new ArrayCollection();
        $this->requestDeletes = new ArrayCollection();
        $this->requestBenefits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_CLIENT';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|RequestAccount[]
     */
    public function getAccountRequest(): Collection
    {
        return $this->AccountRequest;
    }

    public function addAccountRequest(RequestAccount $accountRequest): self
    {
        if (!$this->AccountRequest->contains($accountRequest)) {
            $this->AccountRequest[] = $accountRequest;
            $accountRequest->setClient($this);
        }

        return $this;
    }

    public function removeAccountRequest(RequestAccount $accountRequest): self
    {
        if ($this->AccountRequest->removeElement($accountRequest)) {
            // set the owning side to null (unless already changed)
            if ($accountRequest->getClient() === $this) {
                $accountRequest->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->Accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->Accounts->contains($account)) {
            $this->Accounts[] = $account;
            $account->setClient($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->Accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getClient() === $this) {
                $account->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RequestDelete[]
     */
    public function getRequestDeletes(): Collection
    {
        return $this->requestDeletes;
    }

    public function addRequestDelete(RequestDelete $requestDelete): self
    {
        if (!$this->requestDeletes->contains($requestDelete)) {
            $this->requestDeletes[] = $requestDelete;
            $requestDelete->setClient($this);
        }

        return $this;
    }

    public function removeRequestDelete(RequestDelete $requestDelete): self
    {
        if ($this->requestDeletes->removeElement($requestDelete)) {
            // set the owning side to null (unless already changed)
            if ($requestDelete->getClient() === $this) {
                $requestDelete->setClient(null);
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
            $requestBenefit->setClient($this);
        }

        return $this;
    }

    public function removeRequestBenefit(RequestBenefit $requestBenefit): self
    {
        if ($this->requestBenefits->removeElement($requestBenefit)) {
            // set the owning side to null (unless already changed)
            if ($requestBenefit->getClient() === $this) {
                $requestBenefit->setClient(null);
            }
        }

        return $this;
    }
}
