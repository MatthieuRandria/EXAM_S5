<?php

    namespace App\Controller;

    use App\Repository\UsersRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class UsersController extends AbstractController {
        private UsersRepository $usersRepository;

        public function __construct(UsersRepository $usersRepository) {
            $this->usersRepository = $usersRepository;
        }

        #[Route('/api/users/update-info', name: 'update_info', methods: ['PUT'])]
        public function updateUserInfo(Request $request): JsonResponse {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['idUser'], $data['name'], $data['date_birth'], $data['idGenre'])) {
                return new JsonResponse(['error' => 'Missing required fields.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            try {
                $idUser = (int) $data['idUser'];
                $name = $data['name'];
                $date_birth = new \DateTime($data['date_birth']);
                $idGenre = (int) $data['idGenre'];

                $this->usersRepository->updateUserInfo($idUser, $name, $date_birth, $idGenre);

                return new JsonResponse(['message' => 'User info updated successfully.'], JsonResponse::HTTP_OK);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        #[Route('/api/users/update-password', name: 'update_password', methods: ['PUT'])]
        public function updatePassword(Request $request): JsonResponse {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['idUser'], $data['newPassword'])) {
                return new JsonResponse(['error' => 'Missing required fields.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            try {
                $idUser = (int) $data['idUser'];
                $newPassword = $data['newPassword'];

                $this->usersRepository->updatePassword($idUser, $newPassword);

                return new JsonResponse(['message' => 'Password updated successfully.'], JsonResponse::HTTP_OK);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

?>