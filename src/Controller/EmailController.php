<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(): Response
    {
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(EmailService $emailService): Response
    {
                
        // Configuration du transport SMTP
        // $transport = Transport::fromDsn('gmail://mandaniainaandria7@gmail.com:nqvarumhvradsauj@default');
        $transport = Transport::fromDsn('gmail://cloudprojectsender@gmail.com:miwvbshdqqcdelxm@default');
        // Initialisation du Mailer
        $mailer = new Mailer($transport);
        
        // Création de l'email
        $email = (new Email())
            ->from('cloudprojectsender@gmail.com')
            ->to('notahina.rzf@gmail.com')
            ->subject('Ceci est un test venant de MATTHIEU')
            ->text('MON PC EST HS.')
            ->html('<p>Ceci est un email envoyé avec <strong>Symfony Mailer TAYYYYY</strong>.</p>');
        
        // Envoi de l'email
        $mailer->send($email);
        return new Response('Email sent successfully', 200);

        
    }
}
