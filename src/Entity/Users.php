<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: "Users")]
    class Users {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: "AUTO")]
        #[ORM\Column(type: "integer")]
        private $id_user;

        #[ORM\Column(type: "string", length: 150)]
        private $name;

        #[ORM\Column(type: "string", length: 255)]
        private $mail;

        #[ORM\Column(type: "string", length: 255)]
        private $password;

        #[ORM\Column(type: "date")]
        private $date_birth;

        #[ORM\Column(type: "datetime")]
        private $date_inscription;

        #[ORM\Column(type: "integer")]
        private $tentative_connexion;

        #[ORM\ManyToOne(targetEntity: "Genre")]
        #[ORM\JoinColumn(name: "id_genre", referencedColumnName: "id_genre")]
        private $genre;

        #[ORM\ManyToOne(targetEntity: "Role")]
        #[ORM\JoinColumn(name: "id_role", referencedColumnName: "id_role")]
        private $role;

        // Constructor
        public function __construct(string $name, string $mail, string $password, \DateTime $date_birth, \DateTime $date_inscription, int $tentative_connexion, Genre $genre, Role $role)
        {
            $this->name = $name;
            $this->mail = $mail;
            $this->password = $password;
            $this->date_birth = $date_birth;
            $this->date_inscription = $date_inscription;
            $this->tentative_connexion = $tentative_connexion;
            $this->genre = $genre;
            $this->role = $role;
        }

        // Getter and Setter methods
        public function getId_user(): int {
            return $this->id_user;
        }

        public function setId_user(int $id_user): self {
            $this->id_user = $id_user;
            return $this;
        }

        public function getName(): string {
            return $this->name;
        }

        public function setName(string $name): self {
            $this->name = $name;
            return $this;
        }

        public function getMail(): string {
            return $this->mail;
        }

        public function setMail(string $mail): self {
            $this->mail = $mail;
            return $this;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function setPassword(string $password): self {
            $this->password = $password;
            return $this;
        }

        public function getDate_birth(): \DateTime {
            return $this->date_birth;
        }

        public function setDate_birth(\DateTime $date_birth): self {
            $this->date_birth = $date_birth;
            return $this;
        }

        public function getDate_inscription(): \DateTime {
            return $this->date_inscription;
        }

        public function setDate_inscription(\DateTime $date_inscription): self {
            $this->date_inscription = $date_inscription;
            return $this;
        }

        public function getTentative_connexion(): int {
            return $this->tentative_connexion;
        }

        public function setTentative_connexion(int $tentative_connexion): self {
            $this->tentative_connexion = $tentative_connexion;
            return $this;
        }

        public function getGenre(): Genre {
            return $this->genre;
        }

        public function setGenre(Genre $genre): self {
            $this->genre = $genre;
            return $this;
        }

        public function getRole(): Role {
            return $this->role;
        }

        public function setRole(Role $role): self {
            $this->role = $role;
            return $this;
        }
    }

?>