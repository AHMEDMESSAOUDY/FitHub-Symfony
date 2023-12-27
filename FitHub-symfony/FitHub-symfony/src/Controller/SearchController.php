<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SearchingType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Consultation;


class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
    
    // #[Route('/search1', name: 'ajax_search', methods: ['GET', 'POST'])]
    // public function search(Request $request): Response
    // {
        
    //     $form = $this->createForm(SearchingType::class, [
    //         'action' => $this->generateUrl('ajax_search'),
    //         'method' => 'POST'
    //     ]);
    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data = $form->getData();
    //         // $query = $data['query'];
            
    //         // Call a search method to get results based on the query
    //         //$results = $this->searchDatabase($data);
            
    //         if ($request->isXmlHttpRequest()) {
    //             dd("testAjax");
    //         }
            
    //         // Pass the results to the Twig template
    //         return $this->render('search/result.html.twig', [
    //             'results' => $results
    //         ]);
    //     }
        
    //     return $this->render('search/form.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }

    // private function searchDatabase( $data)
    // {
        
    //     $em = $this->getDoctrine()->getManager();
        
    //     $qb = $em->createQueryBuilder();
        
    //     $qb->select('p')
    //     ->from('App\Entity\Consultation', 'p')
    //     ->where('p.nom LIKE :keyword')
    //     ->setParameter('keyword', '%' . $data['nom'] . '%');
        
    //     if ($data['typeConsultation']) {
    //         $qb->andWhere('p.typeConsultation = :typeConsultation')
    //         ->setParameter('typeConsultation', $data['typeConsultation']);
    //     }
    //     if ($data['dateConsultation']) {
    //         $qb->andWhere('p.dateConsultation = :dateConsultation')
    //         ->setParameter('dateConsultation', $data['dateConsultation']);
    //     }
        
    //     $query = $qb->getQuery();
        
    //     return $query->getResult();
        
        // $qb = $this->getDoctrine()->getRepository(Consultation::class)->createQueryBuilder('e');
        // $qb->where($qb->expr()->like('e.nom', ':nom'))
        //  ->orwhere($qb->expr()->like('e.typeConsultation', ':type'))
        //  //->orwhere($qb->expr()->like('e.dateConsultation', ':date'))
        //   ->setParameters([
            //       'nom' => $query,
            //       'type' => $query,
            // //      'date' => $query,
            //   ]);
            //     //->setParameter('nom', '%' . $query . '%');
            
            // return $qb->getQuery()->getResult();
            
            // $qb = $this->getDoctrine()->getRepository(Consultation::class)->createQueryBuilder('e');
            // $qb->where($qb->expr()->like('e.nom', ':nom'))
            //    ->orwhere($qb->expr()->like('e.typeConsultation', ':type'))
            //    ->orwhere($qb->expr()->like('e.dateConsultation', ':date'))
            //     //->setParameter(['nom', '%' . $query . '%','type', '%' . $query . '%','date', '%' . $query . '%']);
            //      ->setParameters(
                //          ['nom' => $query, 'type'=>$query,
                //              'date'=>$query, //'nomu'=>$searchValue
                //      ]);
                // return $qb->getQuery()->getResult();
            }
        // }
        