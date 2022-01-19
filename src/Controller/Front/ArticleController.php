<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("articles", name="article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("front/articles.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("article/{id}", name="show_article")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("front/article.html.twig", ['article' => $article]);
    }

    /**
     * @Route("search", name="front_search")
     */
    public function frontSearch(Request $request, ArticleRepository $articleRepository)
    {
        $term = $request->query->get('term');
        
        $articles = $articleRepository->searchByTerm($term);

        return $this->render('front/search.html.twig', ['articles' => $articles, 'term' => $term]);
    }
}