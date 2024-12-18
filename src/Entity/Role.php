<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: "role")]
    class Role {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: "AUTO")]
        #[ORM\Column(type: "integer")]
        private $idRole;

        #[ORM\Column(type: "string", length: 50)]
        private $name;
        
        // Constructor
        public function __construct(string $name) {
            $this->name = $name;
        }

        // Getter and Setter methods
        public function getIdRole(): int {
            return $this->idRole;
        }

        public function setIdRole(int $idRole): self {
            $this->idRole = $idRole;
            return $this;
        }

        public function getName(): string {
            return $this->name;
        }

        public function setName(string $name): self {
            $this->name = $name;
            return $this;
        }
    }

?>