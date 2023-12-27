<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Controller\SecurityController as Security;
use App\Form\UtilisateurType;
use App\Form\Utilisateur1Type;
use App\Form\LoginType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Controller\Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


use Symfony\Component\HttpFoundation\JsonResponse;




#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{

    #[Route('/acceuil', name: 'app_utilisateur_acceuil', methods: ['GET'])]
    public function acceuil(Security $security): Response

    {
        if($security->getUser()){
            if(in_array("ROLE_ADMIN",$security->getUser()->getRoles())){
                return $this ->redirectToRoute('app_back');
            }
        }
        return $this->render('utilisateur/acceuil.html.twig', [
           
        ]);
    }

       
     
    //ajout 
    
    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new( Request $request,utilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         
         $utilisateur->setPassword(
            $userPasswordHasher->hashPassword(
                $utilisateur,
                $form->get('password')->getData()

                
         ));
           $utilisateurRepository->save($utilisateur, true);

        return $this->redirectToRoute('app_utilisateur_new');
     }

       return $this->renderForm('utilisateur/new.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form,
    ]);

                   
            
    }
    
    
    //list des utilisateurs
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]

    public function index(UtilisateurRepository $utilisateurRepository,Request $request, PaginatorInterface $paginator): Response
    {       
        $utilisateur = $utilisateurRepository->findAll();

           $utilisateur = $paginator->paginate(
            $utilisateur , /* query NOT result */
            $request->query->getInt(key:'page', default: 1)/*page number*/,
            limit:5/*limit per page*/
        );
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateur,
           
             'pagination' => $utilisateur,
        ]);
        
        
    
    }



    
    
         

        //visualiser par id (recherche)
    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        
        
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }



    //modifier par id 
    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
//suppression
    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        

        
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }
        return $this->redirectToRoute('app_utilisateur_index');

    }



    #[Route('/block/{id}', name: 'app_block_utilisateur')]
   
    public function blockUser(Utilisateur $utilisateur): Response
    {
        $utilisateur->setIsBlocked(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($utilisateur);
        $em->flush();

        return $this->redirectToRoute('app_utilisateur_index');
    }
    #[Route('/unblock/{id}', name: 'app_unblock_utilisateur')]
    public function unblockUser(Utilisateur $utilisateur): Response
    {
        $utilisateur->setIsBlocked(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($utilisateur);
        $em->flush();

        return $this->redirectToRoute('app_utilisateur_index');
    }



    

    }


    

 
    
    





