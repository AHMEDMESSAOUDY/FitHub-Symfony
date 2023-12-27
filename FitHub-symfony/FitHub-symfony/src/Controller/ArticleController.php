<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository ,Request $request, PaginatorInterface $paginator): Response
    {   
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

  



    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository,SluggerInterface $slugger): Response
    {
        $article = new Article();
        $article->setDateArticle(new DateTimeImmutable());
       

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageArticle']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads/articles';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFile = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFile
               );
               $article->setImageArticle($newFile);
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($article);
       $entityManager->flush();
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/article/{id}/like', name: 'app_article_like', methods: ['POST'])]
public function likeArticle(Request $request, Article $article): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $article->setLikeArticle($article->getLikeArticle() + 1);

    $entityManager->persist($article);
    $entityManager->flush();

    return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
}


    

#[Route('/article/{id}/dislike', name: 'app_article_dislike', methods: ['POST'])]
public function dislikeArticle(Request $request, Article $article): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $article->setDislikeArticle($article->getDislikeArticle() + 1);

    $entityManager->persist($article);
    $entityManager->flush();

    return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
}
 /*** @Route("/articles", name="article_list")
*/
public function list(ArticleRepository $articleRepository): Response
{
   $articles = $articleRepository->findAll();

   return $this->render('article/list.html.twig', [
       'articles' => $articles,
   ]);
}
}
