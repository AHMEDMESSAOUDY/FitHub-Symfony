<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Form\SearchingType;
use App\Form\FilterType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\FicheRepository;
use App\Form\User;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{
    // private ConsultationRepository $repoConsultation;

    public function __construct(
       public ConsultationRepository $repoConsultation,
    )
    {
        // $this->repoConsultation = $repoConsultation;
    }

    #[Route('/', name: 'app_consultation_index', methods: ['GET', 'POST'])]
    #[Route('/', name: 'app_consultation_search', methods: ['GET', 'POST'])]
    public function index(Request $request,SerializerInterface $serializer): Response
    {
        $session = $request->getSession();

        if ($session->get('consultations')) {
            $consultations = $session->get('consultations');
            $session->remove('consultations');
        }else {
            $consultations = $this->repoConsultation->findAll();
           }
        
        $form = $this->createForm(SearchingType::class, [
            'action' => $this->generateUrl('app_consultation_search'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()){
                $data = $form->getData();
                $consultations = $this->repoConsultation->findBySearch($data);
                $session->set('consultations', $consultations);

            }
        }

        return $this->renderForm('consultation/index.html.twig', [
            'form' => $form,
            'consultations' => $consultations,
        ]);
    }

    #[Route('/back', name: 'app_consultation_backindex', methods: ['GET'])]
    public function backIndex(Request $request,ConsultationRepository $consultationRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $consultationRepository->findAll(),
            ]);
    }

    #[Route('/backTriA', name: 'app_consultation_backtriAindex', methods: ['GET'])]
    public function backTriAIndex(ConsultationRepository $consultationRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $consultationRepository->findByFilterA(),
            ]);
    }


    #[Route('/backTriD', name: 'app_consultation_backtriDindex', methods: ['GET'])]
    public function backTriDIndex(ConsultationRepository $consultationRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $consultationRepository->findByFilterD(),
            ]);
    }


    #[Route('/backTridA', name: 'app_consultation_backtridAindex', methods: ['GET'])]
    public function backTridAIndex(ConsultationRepository $consultationRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $consultationRepository->findByDateA(),
            ]);
    }


    #[Route('/backTridD', name: 'app_consultation_backtridDindex', methods: ['GET'])]
    public function backTridDIndex(ConsultationRepository $consultationRepository): Response
    {
    return $this->renderForm('consultation/backindex.html.twig', [
                 'consultations' => $consultationRepository->findByDateD(),
            ]);
    }

    #[Route('/statics', name: 'app_consultation_statics', methods: ['GET'])]
    public function statics(FicheRepository $ficheRepository): Response
    {
    //     $monday="2018-01-01";
    //     $sunday="2028-12-31";
    //     //$consultations = $this->repoConsultation->findByPeriod($monday,$sunday);
    //     $consultations = $this->repoConsultation->findAll();
    //     $consNom=[];
    //     $consCount=count($consultations);
    //     foreach($consultations as $consultation ){
    //         $consNom[]= $consultation->getNom();
    //         $date=($consultation->getDateConsultation());
    //     }
        
        $consd=$this->repoConsultation->getConsultationByMonth();
        $const=$this->repoConsultation->getConsultationByType();
        $consy=$this->repoConsultation->getConsultationByYear();
        $fichet=$ficheRepository->getFicheByCategory();
        $fichel=$ficheRepository->getFicheBylength();
        return $this->render('consultation/statics.html.twig',[
            'results' => $consd,
            'resulttype' => $const,
            'resultyear' => $consy,
            'resultsfiche' => $fichet,
            'resultsfichel' => $fichel,
        ]);
    }

    #[Route('/new', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultationRepository $consultationRepository,MailerInterface $mailer,   NormalizerInterface $Normalizer): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRepository->save($consultation, true);
            $email = (new Email())
            ->from('fithub@gmail.com')
            ->to(($consultation->getUtilisateur())->getEmail())
            ->subject('Notification mail!')
            ->text('An appointement have been created!');

            $mailer->send($email);
            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
            $jsonContent = $Normalizer->normalize($consultation, 'json', ['groups' => 'consultation']);
        return new Response(json_encode($jsonContent));
        }

        return $this->renderForm('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRepository->save($consultation, true);

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository,MailerInterface $mailer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $consultationRepository->remove($consultation, true);
            $email = (new Email())
            ->from('fithub@gmail.com')
            ->to(($consultation->getUtilisateur())->getEmail())
            ->subject('Notification mail!')
            ->text('An appointement have been canceled!');

            $mailer->send($email);
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }


    













    // #[Route('/search', name: 'search', methods: ['GET'])]
    // public function search(Request $request): Response
    // {

    //     $form = $this->createForm(SearchType::class);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data = $form->getData();
    //         $query = $data['query'];

    //         // Call a search method to get results based on the query
    //         $results = $this->findByMultiple($query);

    //         // Pass the results to the Twig template
    //         return $this->render('consultation/index.html.twig', [
    //             'results' => $results
    //         ]);
    //     }

    //     return $this->render('consultation/index.html.twig', [
    //         'form' => $form->createView()
    //     ]);


    //     // $consultation = new Consultation();
    //     // $requestString = $request->get('searchValue');
    //     // $consultation= $consultationRepository->findByMultiple($requestString);
    //     // return $this->render('consultation/index.html.twig', [ 'consultations' => $consultation
    //     // ]);
    // }


    // #[Route('/calendar', name:'app_consultation_calendar')]
    // public function consultationsJson(ConsultationRepository $consultationRepository): JsonResponse
    // {
    //     $consultations = $consultationRepository->findAll();

    //     // Define a helper function to format the date and time strings
    //     function formatDate($date) {
    //         return $date->format('Y-m-d');
    //     }

    //     // Map the seances to an array of events in the required format
    //     $events = array_map(function ($consultation) {
    //         return [
    //             'id' => $consultation->getId(),
    //             'nom' => $consultation->getNom(),
    //             'date' => formatDate($consultation->getDateConsultation()),
              
    //         ];
    //     }, $consultations);

    //     // Return the events as a JSON response
    //     return new JsonResponse($events);
    // }


    // #[Route('/search', name: 'ajax_search', methods: ['GET'])]
    // public function searchAction(Request $request)
    // {
    //     $em = $this->getDoctrine()->getManager();
  
    //     $requestString = $request->get('q');
  
    //     $entities =  $em->getRepository('AppBundle:Entity')->findEntitiesByString($requestString);
  
    //     if(!$entities) {
    //         $result['entities']['error'] = "keine Einträge gefunden";
    //     } else {
    //         $result['entities'] = $this->getRealEntities($entities);
    //     }
  
    //     return new Response(json_encode($result));
    // }

    // public function getRealEntities($entities){

    //     foreach ($entities as $entity){
    //         $realEntities[$entity->getId()] = $entity->getFoo();
    //     }
  
    //     return $realEntities;
    // }

}
