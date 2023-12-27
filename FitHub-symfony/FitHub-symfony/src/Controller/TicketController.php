<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Service\TicketService;
use Dompdf\Dompdf;
use TCPDF;

use Dompdf\Options;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Form\AdminTicketType;


use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Repository\TicketRepository;
use App\Entity\Ticket;
use App\Form\TicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;



use Symfony\Component\HttpFoundation\JsonResponse;


class TicketController extends AbstractController
{


    #[Route('/allTickets', name: 'listt')]
    
    public function getTickets():JsonResponse
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
        function formatDateTime($dateTime)
        {
            return $dateTime->format('Y-m-d H:i:s');
        }

        // Map the seances to an array of events in the required format
        $tickets = array_map(function ($tickets) {
            return [
                'id' => $tickets->getId(),
                'nom' => $tickets->getNom(),
                'prix' => $tickets->getPrix(),
                'NombreMax' => $tickets->getNombreMax(),
                'disponibilite' => $tickets->getNombreMax(),
                'email' => $tickets->getEmail(),


                //'tickets' => $tickets ? $tickets->getEmail() . ' ' . $tickets->getNom() : '',
            ];
        }, $tickets);
        return new JsonResponse($tickets);
        
    }

    #[Route('/ticketjson/{id}', name: 'ticketjson')]
    public function ticketId($id, SerializerInterface $serializer, TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->find($id);
        $ticketsArray = [];
    
        if (!$tickets) {
            return new JsonResponse(['message' => 'Ticket not found'], Response::HTTP_NOT_FOUND);
        }
    
        // Map the properties of the ticket to an array
        $ticketsArray[] = [
            'id' => $tickets->getId(),
            'nom' => $tickets->getNom(),
            'prix' => $tickets->getPrix(),
            'nombreMax' => $tickets->getNombreMax(),
            'disponibilite' => $tickets->isDisponibilite(),
            'email' => $tickets->getEmail(),
            //'bookingDate' => $tickets->getBookingDate()->format('Y-m-d H:i:s'),
        ];
    
        // Serialize the array to JSON
        $jsonContent = $serializer->serialize($ticketsArray, 'json');
    
        // Create a new JSON response with the formatted data
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
    

    #[Route('/addTicketJSON/new/{id}', name: 'addTicketJSON')]
public function addTicketJSON(Request $req, NormalizerInterface $normalizer, $id): Response
{
    $em= $this->getDoctrine()->getManager();
    
    // Find the event by ID
    $event = $em->getRepository(Event::class)->find($id);
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }
    
    // Create a new ticket and set its properties
    $ticket = new Ticket();
    $ticket->setNom($req->get('nom'));
    $ticket->setEmail($req->get('email'));
    $ticket->setDisponibilite($req->get('disponibilite'));
    $ticket->setPrix($req->get('prix'));
    $ticket->setNombreMax($req->get('nombreMax'));
    
    // Associate the ticket with the event
    $event->addTicket($ticket);
    $ticket->setEvent($event);

    $em->persist($ticket);
    $em->flush();
    
    $jsonContent = $normalizer->normalize($ticket, 'json', ['groups' => "tickets"]);
    return new Response(json_encode($jsonContent));       
}

    #[Route('/updateTicketJSON/{id}', name: 'updateTicketJSON')]

    public function UpdateTicketJSON($id, Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        $tickets->setNom($request->get('nom'));
        $tickets->setEmail($request->get('email'));
        $tickets->setDisponibilite($request->get('disponibilite'));
        $tickets->setPrix($request->get('prix'));
        $tickets->setNombreMax($request->get('nombreMax'));
        //$dateStr = $request->get('bookingDate');
        //$date = new \DateTime($dateStr);
       // $tickets->setBookingDate($date);
        $em->flush();
        $jsonContent = $Normalizer->normalize($tickets, 'json', ['groups' => 'post:read']);
        return new Response("Update successfully" . json_encode($jsonContent));
    }

   #[Route('/deleteTicketJSON/{id}', name: 'deleteTicketJSON')]
   public function deleteTicketJSON($id, Request $req, EntityManagerInterface $entityManager): JsonResponse
   {
       $em = $this->getDoctrine()->getManager();
       $ticket = $em->getRepository(Ticket::class)->find($id);
       $em->remove($ticket);
       $em->flush();
   
       return new JsonResponse(['status' => 'success', 'message' => 'Ticket deleted.']);
   }

    #[Route("/admin/event/{id}/ticket/new", name:"event_ticket_new")]
    public function new(Request $request, EntityManagerInterface $entityManager, Event $event): Response
{

      // Check if a ticket with null nom and email values already exists
      $existingTicket = $entityManager->getRepository(Ticket::class)->findOneBy([
        'event' => $event,
        'nom' => null,
        'email' => null,
    ]);

    if ($existingTicket) {
        $this->addFlash('error', 'Ticket already exists.');
        return $this->redirectToRoute('admin_ticket_index', ['id' => $event->getId()]);
    }

    $ticket = new Ticket();
    $ticket->setEvent($event);
    $form = $this->createForm(AdminTicketType::class, $ticket);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $ticket->setDisponibilite($form->get('disponibilite')->getData());
        $entityManager->persist($ticket);
        $entityManager->flush();

        $this->addFlash('success', 'Ticket added successfully.');

        return $this->redirectToRoute('admin_ticket_index', ['id' => $event->getId()]);
    }

    return $this->render('ticket_admin/new.html.twig', [
        'event' => $event,
        'form' => $form->createView(),
    ]);
}
    #[Route("admin/ticket", name:"admin_ticket_index")]
   public function index(TicketRepository $ticketRepository): Response
   {
       $tickets = $ticketRepository->findAll();

       return $this->render('ticket_admin/index.html.twig', [
           'tickets' => $tickets,
       ]);
   }
   #[Route('admin/ticket/delete/{id}', name: 'ticket_delete')]
   public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
   {
       if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
           $entityManager->remove($ticket);
           $entityManager->flush();
       }
   
       return $this->redirectToRoute('admin_ticket_index');
   }

   #[Route('user/event/{id}/book', name: 'event_book')]
   public function book(Request $request, $id, TicketRepository $ticketRepository,MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
   {

       $event = $this->getDoctrine()
           ->getRepository(Event::class)
           ->find($id);
         
           
           $ticket = new Ticket();
           $ticket->setEvent($event);
           
           
           $bookingDate = new \DateTime();
$ticket->setBookingDate($bookingDate);

$existingTicket = $ticketRepository->findOneBy(['event' => $event], ['id' => 'ASC']);
if ($existingTicket) {
    $ticket->setPrix($existingTicket->getPrix());
    $ticket->setNombreMax($existingTicket->getNombreMax());
    $ticket->setDisponibilite($existingTicket->isDisponibilite());
} else {
    $ticket->setPrix(null);
    $ticket->setNombreMax(null);
    $ticket->setDisponibilite(null);
}
$ticketCount = $ticketRepository->count(['event' => $event]);

if ($ticketCount >= $ticket->getNombreMax()) {
    // Return a response indicating that the event is not available
    $ticket->setDisponibilite(false);

    return $this->render('ticket/unavailable.html.twig', [
        'event' => $event,
    ]);
}         

       $form = $this->createForm(TicketType::class, $ticket);
       $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();
            $email = $form->get('email')->getData();



            $path = $this->getParameter('kernel.project_dir').'/public';
            $pathqr = $this->getParameter('kernel.project_dir').'/public/image/png';

            $result=Builder::create()
                ->writer(new PngWriter())
                ->data("id : ".$ticket->getId()."  |  "."Date Event :".$event->getDate()->format("Y/m/d"). " | ".$ticket->getBookingDate()->format("Y/m/d") )
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                ->labelText("")
                ->logoPath($pathqr."/fithub4.png")
                ->labelAlignment(new LabelAlignmentCenter())
                ->labelMargin(new Margin(15, 5, 5, 5))
                ->logoResizeToWidth('100')
                ->logoResizeToHeight('100')
                ->build();


            $namePng =uniqid('',''). '.png';
            $result->saveToFile( $pathqr.'/qr-code/'.$namePng);
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->Rect(10, 10, 190, 265, 'D');

            $pdf->Image($pathqr."/logo2.jpg", 15, 15, 50, 0,);
            $pdf->SetXY(60, 50);


            $pdf->SetMargins(15, 15, 15);
            $pdf->SetFillColor(153, 255, 255);
            $pdf->SetFont('Helvetica', 'B', 20);
            
            $pdf->SetMargins(25, 25, 25); 
            
            $pdf->WriteHTML('<p style="color: #152238; font-size: 20px; margin-left:15px, padding:10px ">We hope your ticket finds you well</p> </br> </br>');

            $pdf->SetFont('Helvetica', 'B', 16);
            $pdf->Cell(50, 10, 'Event :', 0, 0);
            $pdf->SetFont('Helvetica', '', 16);
            $pdf->Cell(0, 10, $event->getNomEvent(), 0, 1);

            $pdf->SetFont('Helvetica', 'B', 16);
            $pdf->Cell(50, 10, 'Guest:', 0, 0);
            $pdf->SetFont('Helvetica', '', 16);
            $pdf->Cell(0, 10, $ticket->getNom(), 0, 1);
            
            $pdf->SetFont('Helvetica', 'B', 16);
            $pdf->Cell(50, 10, 'Event address:', 0, 0);
            $pdf->SetFont('Helvetica', '', 16);
            $pdf->Cell(0, 10, $event->getLocation(), 0, 1);
            
            $pdf->SetFont('Helvetica', '', 14);
            $pdf->Cell(0, 10, 'Please present your ticket at the reception to access the event hall', 0, 1);
            
            $pdf->Image($pathqr.'/qr-code/'.$namePng);
            $pdfData = $pdf->Output('', 'S');
            
            // Mailing
            // Create the message
            $message = (new TemplatedEmail())
                ->from('fithubg@gmail.com')
                ->to($email)
                ->subject('Confirmation de Reservation')
                ->html("<p>Bonjour  , </p><p>Ceci est une confirmation de votre réservation Numero {$ticket->getId()}.</p><p>Merci pour votre confiance!</p><p>Cordialement,<br/>L'équipe Fithub</p>")
                ->attach($pdfData, 'ticket.pdf', 'application/pdf')
                ->embedFromPath($pathqr.'/qr-code/'.$namePng);
            $mailer->send($message);
            
            return $this->redirectToRoute('app_event');
        }

        return $this->render('ticket/index.html.twig', [
            'ticket' => $ticket,
            'event' => $event,
            'form' => $form->createView(),
        ]);
   }

    #[Route("admin/ticket/{id}/edit", name:"admin_ticket_edit")]
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(AdminTicketType::class, $ticket);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setDisponibilite($form->get('disponibilite')->getData());
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('admin_ticket_index');
        }
    
        return $this->render('ticket_admin/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /*
     #[Route("/PDF_Reserver", name:"PDF_Reserver")]
    public function PDF_Reserver(TicketRepository $ticketRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('ticket/ticketpdf.html.twig', [
            'ticket' => $ticketRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste_Des_Tickets.pdf", [
            "ticket" => true
        ]);
    }*/
   
}