<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: "role")]
    class Role {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: "AUTO")]
        #[ORM\Column(type: "integer")]
        private $id_role;

        #[ORM\Column(type: "string", length: 50)]
        private $name;
        
        // Constructor
        public function __construct(string $name) {
            $this->name = $name;
        }

        // Getter and Setter methods
        public function getId_role(): int {
            return $this->id_role;
        }

        public function setId_role(int $id_role): self {
            $this->id_role = $id_role;
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