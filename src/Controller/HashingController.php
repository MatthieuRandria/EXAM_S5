<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Token;
use App\Repository\GenreRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/hashing')]
class HashingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route('/update-passwords', name: 'update_passwords', methods: ['POST'])]
    public function updatePasswords(): JsonResponse
    {
        try {
            $users = $this->entityManager->getRepository(Users::class)->findAll();
            $updatedCount = 0;

            foreach ($users as $user) {
                $plainPassword = $user->getPassword(); // Récupère le mot de passe actuel non hashé
                if ($plainPassword) {
                    $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword);
                    $updatedCount++;
                }
            }

            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Passwords updated successfully',
                'updated_count' => $updatedCount
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Error updating passwords',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, GenreRepository $genre_repo, RoleRepository $role_repo): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if (!isset($content['mail'], $content['password'], $content['name'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $user = new Users();
        $user->setMail($content['mail']);
        $user->setName($content['name']);

        // Hash the password before storing
        $hashedPassword = $this->passwordHasher->hashPassword($user, $content['password']);
        echo $hashedPassword;
        $user->setPassword($hashedPassword);

        // Set other required fields
        $user->setDateBirth(new \DateTime($content['date_birth'] ?? 'now'));
        $user->setDateInscription(new \DateTime());
        $user->setTentativeConnexion(0);

        // Set Genre and Role (assuming you have repositories or default values)
        $genre = $genre_repo->find($content['idgenre'] ?? 1);
        $role = $role_repo->find($content['idrole'] ?? 2);

        $user->setGenre($genre);
        $user->setRole($role);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Registration failed', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $content = $request->getContent();
        dump('Content : ' . $content);
        $data = json_decode($content, true);
        if (!isset($data['mail'], $data['password'])) {
            return new JsonResponse(['message' => 'Missing email or password'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['mail' => $data['mail']]);
        dump('Mail : ' . $user->getMail());
        dump('Password : ' . $user->getPassword());
        dump('Name : ' . $user->getName());
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        // Verify the password using the password hasher
        if (!$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            // Increment failed login attempts
            $user->setTentativeConnexion($user->getTentativeConnexion() + 1);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

            // Reset failed login attempts on successful login
            $user->setTentativeConnexion(0);

            // Generate new token
            $token = new Token();
            $token->setToken(bin2hex(random_bytes(32)));
            $token->setUser($user);

            $this->entityManager->persist($token);
            $this->entityManager->flush();

            return new JsonResponse([
                'token' => $token->getToken(),
                'user' => [
                    'id' => $user->getIdUser(),
                    'mail' => $user->getMail(),
                    'name' => $user->getName(),
                    'role' => $user->getRole()->getName()
                ]
            ]);
    }


    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $token = $request->headers->get('X-AUTH-TOKEN');
        $tokenEntity = $this->entityManager->getRepository(Token::class)->findValidToken($token);

        if ($tokenEntity) {
            $this->entityManager->remove($tokenEntity);
            $this->entityManager->flush();
        }

        return new JsonResponse(['message' => 'Logged out successfully']);
    }
}
