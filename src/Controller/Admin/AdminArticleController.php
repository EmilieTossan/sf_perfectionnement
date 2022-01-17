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
    /**
     * @Route("admin/articles", name="admin_article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("admin/articles.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("admin/article/{id}", name="admin_show_article")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("admin/article.html.twig", ['article' => $article]);
    }

    /**
     * @Route("admin/create/article", name="admin_create_article")
     */
    public function adminArticleCreate(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_article_list");
        }

        return $this->render("admin/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    /**
     * @Route("admin/update/article", name="admin_update_article")
     */
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

    /**
     * @Route("admin/delete/article", name="admin_delete_article")
     */
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