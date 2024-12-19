<?php

    namespace App\Controller;

    use App\Repository\UsersRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class UsersController extends AbstractController {
        private UsersRepository $usersRepository;
        private EmailController $emailController;
        private int $max_attempt=3;

        public function __construct(UsersRepository $usersRepository, EmailController $emailController) {
            $this->usersRepository = $usersRepository;
            $this->emailController=$emailController;
        }

        #[Route('/reinitialise/{mail}', name: 'reinitialise', methods: ['POST'])]
        public function reinitialise(string $mail):JsonResponse{
            $user=$this->usersRepository->find($mail);
            $this->usersRepository->updateAttempt($user, 0);
            return new JsonResponse(['status' => 'Reinitialisation Success'], 200);
        }

        #[Route('/send_reinitialisation/{mail}', name: 'send_reinitialisation', methods: ['POST'])]
        public function sendReinitialisation(string $mail){
            return $this->emailController->sendEmail("matthieuandrianarisoa@gmail.com", "Reinitialise", "TAY");
        }

        #[Route('/check_pin/{mail}', name: 'check_pin', methods: ['POST'])]
        public function checkPin(Request $request, string $mail): JsonResponse {
            $data=json_decode($request->getContent(), true);
            $pin_user=$data['pin'] ?? null;

            $user=$this->usersRepository->find($mail);
            
            $user_attempt=$user->getTentative_connexion();
            if($user_attempt>$this->max_attempt){
                return new JsonResponse(['error' => 'Max attempt, try to reinitialise via email'], 400);
            }
            if($pin_user != 1111){
                $this->usersRepository->updateAttempt($user, $user_attempt+1);
                return new JsonResponse(['error' => 'Pin incorrect'], 400);
            }
            else{
                $this->usersRepository->updateAttempt($user, 0);
                return new JsonResponse(['status' => 'Pin Success'], 200);
            }
        }

        #[Route('/check_password/{mail}', name: 'check_password', methods: ['POST'])]
        public function checkPassword(Request $request, string $mail): JsonResponse {
            $data=json_decode($request->getContent(), true);
            $password=$data['password'] ?? null;

            $user=$this->usersRepository->find($mail);
            
            $user_attempt=$user->getTentative_connexion();
            if($user_attempt>$this->max_attempt){
                return new JsonResponse(['error' => 'Max attempt, try to reinitialise via email'], 400);
            }
            if(password_verify($password, $user->getPassword())==false){
                $this->usersRepository->updateAttempt($user, $user_attempt+1);
                return new JsonResponse(['error' => 'Password incorrect'], 400);
            }
            else{
                return new JsonResponse(['status' => 'Password Success'], 200);
            }
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