<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer=$mailer;
    }

    public function sendEmail(string $recipient, string $subject, string $message) : void
    {
        try{
            // Configuration du transport SMTP
            $transport = Transport::fromDsn('gmail://mandaniainaandria7@gmail.com:nqvarumhvradsauj@default');
            
            // Initialisation du Mailer
            $mailer = new Mailer($transport);
        
            // Création de l'email
            $email = (new Email())
                ->from('mandaniainaandria7@gmail.com')
                ->to($recipient)
                ->subject($subject)
                ->text($message)
                ->html("<p>{$message}</p>");
        
            // Envoi de l'email
            $this->mailer->send($email);
        }
        catch(\Exception $e){
            throw $e;
        }
        
    }

}
