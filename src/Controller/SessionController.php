<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/session')]
class SessionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/lifetime', name: 'session_update_duration', methods: ['PUT'])]
    public function updateDuration(Request $request): JsonResponse
    {
        // Décoder le contenu JSON
        $data = json_decode($request->getContent(), true);

        // Vérification de $data pour diagnostiquer le problème
        if ($data === null) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Invalid JSON payload',
                'raw_body' => $request->getContent()
            ], 400);
        }

        // Récupérer les données
        $id = $data['id'] ?? null;
        $duration = $data['duration'] ?? null;

        // Validation des données
        if (!$id || !$duration || $duration <= 0) {
            return new JsonResponse(['error' => 'Invalid ID or duration'], 400);
        }

        // Rechercher la session existante par ID
        $session = $this->entityManager->getRepository(Session::class)->find($id);

        if (!$session) {
            return new JsonResponse(['error' => 'Session not found'], 404);
        }

        try {
            // Mettre à jour la durée de la session
            $session->setDuration($duration);

            // Sauvegarder les modifications
            $this->entityManager->persist($session);
            $this->entityManager->flush();

            // Ajouter un cookie avec la nouvelle durée
            $cookie = new Cookie(
                'session_lifetime',  // Nom du cookie
                $duration,           // Valeur du cookie
                time() + $duration,  // Expiration (durée actuelle + durée configurée)
                '/',                 // Chemin
                null,                // Domaine
                false,               // HTTPS uniquement
                true,                // HTTP uniquement
                false,               // Raw
                Cookie::SAMESITE_LAX // Politique SameSite
            );

            $response = new JsonResponse([
                'success' => true,
                'message' => 'Duration updated successfully',
                'session_id' => $id,
                'duration' => $duration
            ]);
            $response->headers->setCookie($cookie);

            return $response;
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/duration', name: 'api_session_duration_get', methods: ['GET'])]
    public function getSessionDuration(Request $request): JsonResponse
    {
        // Lire le cookie de la requête
        $cookieDuration = $request->cookies->get('session_lifetime');

        // Récupérer la session la plus récente de la base de données
        $session = $this->entityManager->getRepository(Session::class)
            ->findOneBy([], ['idSession' => 'DESC']);

        // Calculer la durée actuelle
        $duration = $session ? $session->getDuration() : null;

        return new JsonResponse([
            'duration' => $duration,
            'cookie_duration' => $cookieDuration
        ]);
    }
}
