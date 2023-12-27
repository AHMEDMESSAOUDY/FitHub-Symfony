<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Controller\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


 

  
   


class UtilisateurApiController extends AbstractController
{
    #[Route('/user/signup', name: 'app_utilisateur_signup')]
    public function signupAction(Request $request , UserPasswordEncoderInterface $passwordEncoder)
    {
        $email=$request->query->get("email");
        $nom=$request->query->get("nom");
        $prenom=$request->query->get("prenom");
        $adresse=$request->query->get("adresse");
        $telephone=$request->query->get("telephone");
        $password=$request->query->get("password");
          

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid");
        }
        $user= new Utilisateur();
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setIsVerified(true);
        $user->setAdresse($adresse);
        $user->setTelephone($telephone);


        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        
        try {
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("Account is created",200);

        }catch(\Exception $ex){
            return new Response("exception".$ex->getMessage());
        }

        }

        #[Route('/user/signin', name: 'app_utilisateur_signin')]
        
        public function signinAction(Request $request)
        {

            $email=$request->query->get("email"); 
            $password=$request->query->get("password");
            $em=$this->getDoctrine()->getManager();
            $user=$em->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);


            if($user){
                if(password_verify($password,$user->getPassword())){
                    $serializer=new Serializer([new ObjectNormalizer()]);
                    $formatted=$serializer->normalize($user);
                    return new JsonResponse($formatted);
                }
                else{
                    return new Response("password not found");
                }
            }

            else{

                return new Response("user not found");


            
            }
        }

        #[Route('/user/editprofile', name: 'app_utilisateur_profile')]

        public function editprofile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
        {

            $id=$request->get("id");
            $nom=$request->query->get("nom"); 
            $prenom=$request->query->get("prenom"); 
            $telephone=$request->query->get("telephone"); 
            $adresse=$request->query->get("adresse"); 
            $email=$request->query->get("email"); 
            $password=$request->query->get("password");
            $em=$this->getDoctrine()->getManager();
            $user=$em->getRepository(Utilisateur::class)->find($id);

            $user->setEmail($email);
            $user->setIsVerified(true);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setAdresse($adresse);
            $user->setTelephone($telephone);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));



            try {
                $em=$this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return new JsonResponse("Succes",200);
    
            }catch(\Exception $ex){
                return new Response("failed".$ex->getMessage());
            }
    
        }


    }

