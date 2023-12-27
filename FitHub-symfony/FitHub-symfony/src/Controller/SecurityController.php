<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       if ($this->getUser()) {
           return $this->redirectToRoute('app_utilisateur_acceuil');
        }

        // get the login error if there is one
        //Elle récupère également les erreurs d'authentification et le dernier nom d'utilisateur entré par l'utilisateur
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
public function logout(): RedirectResponse
{
    // Redirige vers la page d'accueil
    return $this->redirectToRoute('app_front');
}
    
}
