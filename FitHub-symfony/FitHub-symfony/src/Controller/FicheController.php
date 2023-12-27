<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Form\FicheType;
use App\Repository\FicheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\SecurityController as Security;

#[Route('/fiche')]
class FicheController extends AbstractController
{
    #[Route('/', name: 'app_fiche_index', methods: ['GET'])]
    public function index(FicheRepository $ficheRepository): Response
    {
       
        //return $this->render('utilisateur/acceuil.html.twig', [
           
        //]);
        return $this->render('fiche/index.html.twig', [
            'fiches' => $ficheRepository->findAll(),
        ]);
    }

    #[Route('/back', name: 'app_fiche_backindex', methods: ['GET'])]
    public function backIndex(FicheRepository $ficheRepository): Response
    {
        return $this->render('fiche/backindex.html.twig', [
            'fiches' => $ficheRepository->findAll(),
        ]);
    }



    #[Route('/backTriA', name: 'app_fiche_backtriAindex', methods: ['GET'])]
    public function backTriAIndex(FicheRepository $ficheRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $ficheRepository->findByFilterA(),
            ]);
    }


    #[Route('/backTriD', name: 'app_fiche_backtriDindex', methods: ['GET'])]
    public function backTriDIndex(FicheRepository $ficheRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $ficheRepository->findByFilterD(),
            ]);
    }

    #[Route('/new', name: 'app_fiche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheRepository $ficheRepository,Security $security): Response
    {
        if($security->getUser()){
            if(in_array("ROLE_MEMBRE",$security->getUser()->getRoles())){
                return $this ->redirectToRoute('app_utilisateur_acceuil');
            }
        }
        $fiche = new Fiche();
        $form = $this->createForm(FicheType::class, $fiche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheRepository->save($fiche, true);

            return $this->redirectToRoute('app_fiche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche/new.html.twig', [
            'fiche' => $fiche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_show', methods: ['GET'])]
    public function show(Fiche $fiche): Response
    {
        return $this->render('fiche/show.html.twig', [
            'fiche' => $fiche,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fiche $fiche, FicheRepository $ficheRepository): Response
    {
        $form = $this->createForm(FicheType::class, $fiche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheRepository->save($fiche, true);

            return $this->redirectToRoute('app_fiche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche/edit.html.twig', [
            'fiche' => $fiche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_delete', methods: ['POST'])]
    public function delete(Request $request, Fiche $fiche, FicheRepository $ficheRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fiche->getId(), $request->request->get('_token'))) {
            $ficheRepository->remove($fiche, true);
        }

        return $this->redirectToRoute('app_fiche_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/statics', name: 'app_fiche_statics', methods: ['GET'])]
    public function statics(FicheRepository $ficheRepository): Response
    {        
        $fichet=$ficheRepository->getFicheByCategory();
        // $const=$this->repoConsultation->getConsultationByType();
        // $consy=$this->repoConsultation->getConsultationByYear();
        return $this->render('consultation/statics.html.twig',[
            'resultsfiche' => $fichet,
            // 'resulttype' => $const,
            // 'resultyear' => $consy,
        ]);
    }

   
}
