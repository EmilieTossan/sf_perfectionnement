<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("front/articles.html.twig", ['articles' => $articles]);
    }

    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("front/article.html.twig", ['article' => $article]);
    }

    public function frontSearch(Request $request, ArticleRepository $articleRepository)
    {
        $term = $request->query->get('term');
        
        $articles = $articleRepository->searchByTerm($term);

        return $this->render('front/search.html.twig', ['articles' => $articles, 'term' => $term]);
    }
}