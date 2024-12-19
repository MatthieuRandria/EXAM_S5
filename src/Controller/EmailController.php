<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class EmailController extends AbstractController
{
    private $emailSender;

    public function __construct(EmailService $emailSender)
    {
        $this->emailSender=$emailSender;
    }
  
    #[Route('/send_email/{recipient}/{subject}/{message}', name:'send_email', methods:'POST')]
    public function sendEmail ($recipient, $subject, $message): JsonResponse
    {
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
