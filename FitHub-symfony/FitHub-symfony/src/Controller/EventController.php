<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use App\Entity\Ticket;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\TicketType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Symfony\Component\Serializer\SerializerInterface;



class EventController extends AbstractController
{

    #[Route('/allEvents', name: 'list')]
    public function getEvents(SerializerInterface $serializer): JsonResponse
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
    
        $normalizedEvents = $serializer->normalize($events, null, [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    
        return new JsonResponse($normalizedEvents);
    }
    #[Route('/event/{id}', name: 'eventjson')]
    public function eventId($id, SerializerInterface $serializer, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->find($id);
        $eventArray = [];
    
        if (!$events) {
            return new JsonResponse(['message' => 'event not found'], Response::HTTP_NOT_FOUND);
        }
    
        function formatDateTime($dateTime)
        {
            return $dateTime->format('Y-m-d H:i:s');
        }

        // Map the seances to an array of events in the required format
        $eventArray[] = 
             [
                'id' => $events->getId(),
                'nomE' => $events->getNomEvent(),
                'date' => formatDateTime($events->getDate()),
                'type' => $events->getType(),
                'description' => $events->getDescription(),
                'image'=>$events->getImage(),

                //'tickets' => $tickets ? $tickets->getEmail() . ' ' . $tickets->getNom() : '',
            ];
        
        // Serialize the array to JSON
        $jsonContent = $serializer->serialize($eventArray, 'json');
    
        // Create a new JSON response with the formatted data
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
    #[Route('/addEventJSON/new', name: 'addEventJSON')]
    public function addEventJSON(Request $req,NormalizerInterface $normalizer): Response
    {
        $em= $this->getDoctrine()->getManager();
        $events = new Event();
        $events->setNomEvent($req->get('nomEvent'));
        $events->setDescription($req->get('description'));
        $events->setLocation($req->get('location'));
        $events->setType($req->get('type'));
        $dateStr = $req->get('date');
$date = new \DateTime($dateStr);
$events->setDate($date);
        
        $events->setImage($req->get('image'));
        $em->persist($events);
        $em->flush();
        $jsonContent = $normalizer->normalize($events,'json',['groups'=>'events']);
        dd($events);

        return new  Response (json_encode($jsonContent));       
    
    }

    #[Route('/deleteEventJSON/{id}', name: 'deleteEventJSON')]
        
        public function deleteEventJSON($id, Request $req, EntityManagerInterface $entityManager): JsonResponse
   {
       $em = $this->getDoctrine()->getManager();
       $event = $em->getRepository(Event::class)->find($id);
       $em->remove($event);
       $em->flush();
   
       return new JsonResponse(['status' => 'success', 'message' => 'event deleted.']);
   }
    
    

   #[Route('/updateEventJSON/{id}', name: 'updateEventJSON')]

   public function UpdateEventJSON($id, Request $req, NormalizerInterface $Normalizer)
   {
       $em = $this->getDoctrine()->getManager();
       $events = $this->getDoctrine()->getRepository(Event::class)->find($id);
       $events->setNomEvent($req->get('nomEvent'));
       $events->setDescription($req->get('description'));
       $events->setLocation($req->get('location'));
       $events->setType($req->get('type'));
       $dateStr = $req->get('date');
$date = new \DateTime($dateStr);
$events->setDate($date);
       
       $events->setImage($req->get('image'));
       $em->flush();
       $jsonContent = $Normalizer->normalize($events, 'json', ['groups' => 'post:read']);
       return new Response("Update successfully" . json_encode($jsonContent));
   }


    
    #[Route("/user/event", name:"app_event")]
    public function eventsList(EventRepository $eventRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $events = $eventRepository->findBy([], ['date' => 'DESC']);
    
        $pagination = $paginator->paginate(
            $events,
            $request->query->getInt('page', 1),
            2
        );
    
        return $this->render('event/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


   
    
     #[Route("/user/event/{id}/details", name:"event_details")]
    public function eventDetails(Event $event): Response
    {
        return $this->render('event/detail.html.twig', [
            'event' => $event,
        ]);
    }


   

    #[Route('admin/event', name: 'app_event_admin')]
    public function adminindex(EventRepository $eventRepository,Request $request,PaginatorInterface $paginator): Response

    {
        $events = $eventRepository->findBy([], ['date' => 'DESC']);
    
        $pagination = $paginator->paginate(
            $events,
            $request->query->getInt('page', 1),
            2
        );
    
        return $this->render('eventadmin/index.html.twig', [
            'pagination' => $pagination,
        ]);
   
    }

    #[Route('admin/event/new', name: 'app_event_admin_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($event);
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('event_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the  file name
                // instead of its contents
                $event->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_event_admin');
        }

        return $this->render('eventadmin/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/event/show/{id}', name: 'app_event_admin_show')]
    public function show(Event $event): Response
    {
        return $this->render('eventadmin/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('admin/event/edit/{id}', name: 'event_edit')]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_admin');
        }

        return $this->render('eventadmin/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/event/delete/{id}', name: 'event_delete')]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_admin');
    }
}