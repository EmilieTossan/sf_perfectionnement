<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{
    public function adminArticleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("admin/articles.html.twig", ['articles' => $articles]);
    }

    public function adminShowArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("admin/article.html.twig", ['article' => $article]);
    }

    public function adminArticleCreate(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $article->setDate(new \DateTime("NOW"));
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_article_list");
        }

        return $this->render("admin/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    public function adminArticleUpdate(
        $id, 
        ArticleRepository $articleRepository, 
        Request $request, 
        EntityManagerInterface $entityManagerInterface)
    {
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_article_list");
        }

        return $this->render("admin/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    public function adminArticleDelete(
        $id,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManagerInterface)
    {
        $article = $articleRepository->find($id);
        
        $entityManagerInterface->persist($article);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_article_list");
    }
}