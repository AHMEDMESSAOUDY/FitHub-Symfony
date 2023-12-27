<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\Commentaire1Type;
use App\Repository\CommentaireRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;






#[Route('/article/{id}/commentaireu')]
class CommentaireuController extends AbstractController
{

    #[Route('/new', name: 'app_commentaireu_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Article $article ,CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $commentaire ->setDateCommentaire(new DateTimeImmutable());
        $commentaire->setArticle($article);
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($commentaire->getDescriptionCommentaire());
$commentaire->setDescriptionCommentaire($rr);
            
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_articleu_show', [
                'id'=>$article->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaireu/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    public function filterwords($text)
    {
        $filterWords = array('stupid', 'bhim', 'msatek', 'fuck', 'slut', 'bitch');
        $filterCount = count($filterWords);
        $str = "";
        $data = preg_split('/\s+/',  $text);
        foreach($data as $s){
            $g = false;
            foreach ($filterWords as $lib) {
                if($s == $lib){
                    $t = "";
                    for($i =0; $i<strlen($s); $i++) $t .= "*";
                    $str .= $t . " ";
                    $g = true;
                    break;
                }
            }
            if(!$g)
            $str .= $s . " ";
        }
        return $str;
    }

}
