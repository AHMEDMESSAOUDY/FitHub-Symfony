<?php

namespace App\Controller;
namespace App\Controller;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('abidi.chaima@esprit.tn')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);


        return $this->render('/reset_password/check_email.html.twig');
    }
}

