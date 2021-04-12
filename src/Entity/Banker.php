<?php

namespace App\Entity;

use App\Repository\BankerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=BankerRepository::class)
 * @UniqueEntity(fields={"email"}, message="Ce mail est déja utilisé")
 */
class Banker extends User
{

    /**
     * @ORM\OneToMany(targetEntity=RequestAccount::class, mappedBy="Banker")
     */
    private $AccountRequest;

    public function __construct()
    {
        $this->AccountRequest = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return parent::getId();
    }

    public function getEmail(): ?string
    {
        return parent::getEmail();
    }

    public function setEmail(string $email): self
    {
        parent::setEmail($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) parent::getUsername();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_BANKER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        parent::setRoles($roles);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) parent::getPassword();
    }

    public function setPassword(string $password): self
    {
        parent::setPassword($password);

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
            $accountRequest->setBanker($this);
        }

        return $this;
    }

    public function removeAccountRequest(RequestAccount $accountRequest): self
    {
        if ($this->AccountRequest->removeElement($accountRequest)) {
            // set the owning side to null (unless already changed)
            if ($accountRequest->getBanker() === $this) {
                $accountRequest->setBanker(null);
            }
        }

        return $this;
    }
}
