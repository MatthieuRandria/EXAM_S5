<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class EmailController extends AbstractController
{
    private $emailSender;

    public function __construct(EmailService $emailSender)
    {
        $this->emailSender=$emailSender;
    }
  
    #[Route('/send_email', name:'send_email', methods:'POST')]
    public function sendEmail (Request $request): JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $recipient=$data['email'] ?? null;
        $subject=$data['subject'] ?? 'Notification';
        $message=$data['message'] ?? null;
        if(!$recipient || !$message){
            return new JsonResponse(['error' => 'Email and message are required'], 400);
        }
        try{
            $this->emailSender->sendEmail($recipient, $subject, $message);
            return new JsonResponse(['status' => 'Email sent successfully'], 200);
        }
        catch(\Exception $e){
            return new JsonResponse(['error' => $e], 500);
        }
    }
}
