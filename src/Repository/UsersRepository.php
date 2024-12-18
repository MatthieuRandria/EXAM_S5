<?php

    namespace App\Repository;

    use App\Entity\User;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;
    use Doctrine\ORM\EntityManagerInterface;

    class UsersRepository extends ServiceEntityRepository {
        private EntityManagerInterface $entityManager;

        public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager) {
            parent::__construct($registry, User::class);
            $this->entityManager = $entityManager;
        }

        public function updateUserInfo(int $idUser, string $name, \DateTime $date_birth, int $idGenre): void {
            $user = $this->find($idUser);
            if ($user) {
                $genre = $this->entityManager->getRepository('App:Genre')->find($idGenre);
                if ($genre) {
                    $user->setName($name);
                    $user->setDate_birth($date_birth);
                    $user->setGenre($genre);
                    $this->entityManager->flush();
                } else {
                    throw new \Exception('Genre not found');
                }
            } else {
                throw new \Exception('User not found');
            }
        }

        public function updatePassword(int $idUser, string $newPassword): void {
            $user = $this->find($idUser);
            if ($user) {
                $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT)); // Hachage du mot de passe
                $this->entityManager->flush();
            } else {
                throw new \Exception('User not found');
            }
        }
    }

?>