<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idtoken', type: 'integer')]
    private ?int $idToken = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $token = null;

    #[ORM\Column(name: 'databaseate_created ', type: 'datetime')]
    private ?\DateTimeInterface $date_created;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'id_user', nullable: false)]
    private Users $user;

    public function __construct()
    {
        $this->date_created = new \DateTime();
    }

    public function getIdToken(): ?int
    {
        return $this->idToken;
    }

    public function setIdToken(int $idToken): self
    {
        $this->idToken = $idToken;
        return $this;
    }

    public function getToken(): ?string {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self {
        $this->date_created = $date_created;
        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }
}
