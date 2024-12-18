<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: "genre")]
    class Genre {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: "AUTO")]
        #[ORM\Column(type: "integer")]
        private $idGenre;

        #[ORM\Column(type: "string", length: 50)]
        private $type;
        
        // Constructor
        public function __construct(string $type) {
            $this->type = $type;
        }

        // Getter and Setter methods
        public function getIdGenre(): int {
            return $this->idGenre;
        }

        public function setIdGenre(int $idGenre): self {
            $this->idGenre = $idGenre;
            return $this;
        }

        public function getType(): string {
            return $this->type;
        }

        public function setType(string $type): self {
            $this->type = $type;
            return $this;
        }
    }

?>