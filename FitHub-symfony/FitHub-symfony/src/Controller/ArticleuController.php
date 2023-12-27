<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Article1Type;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/articleu')]
class ArticleuController extends AbstractController
{
    #[Route('/', name: 'app_articleu_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,Request $request, PaginatorInterface $paginator): Response
    {$articles = $paginator->paginate(
        $articleRepository , /* query NOT result */
        $request->query->getInt(key:'page', default: 1)/*page number*/,
        limit:2/*limit per page*/
    );
        return $this->render('articleu/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

   

    #[Route('/{id}', name: 'app_articleu_show', methods: ['GET'])]
    public function show(Article $articles): Response
    {   
        $comments = $articles->getCommentaires();
        return $this->render('articleu/show.html.twig', [
            'articles' => $articles,
            'comments'=> $comments,
        ]);
    }
    #[Route('/articleu/{id}/like', name: 'app_articleu_like', methods: ['POST'])]
    public function likeArticle(Request $request, Article $article): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $article->setLikeArticle($article->getLikeArticle() + 1);
    
        $entityManager->persist($article);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_articleu_show', ['id' => $article->getId()]);
    }
    
    
        
    
    #[Route('/articleu/{id}/dislike', name: 'app_articleu_dislike', methods: ['POST'])]
    public function dislikeArticle(Request $request, Article $article): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $article->setDislikeArticle($article->getDislikeArticle() + 1);
    
        $entityManager->persist($article);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_articleu_show', ['id' => $article->getId()]);
    }
    /**
     * @Route("/articles", name="article_list")
     */
    public function list(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }
}
