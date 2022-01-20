<?php

namespace App\Controller\Front;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("like/article/{id}", name="article_like")
     */
    public function likeArticle(
        $id,
        ArticleRepository $articleRepository,
        LikeRepository $likeRepository,
        EntityManagerInterface $entityManagerInterface
    ){
        $article = $articleRepository->find($id);
        $user = $this->getUser();

        if(!$user){
            return $this->json(
                [
                    'code' => 403,
                    'meessage' => "Vous devez vous connecter."
                ], 403
            );
        }

        if ($article->isLikedByUser($user)){
            $like = $likeRepository->findOneBy(
                [
                    'article' => $article,
                    'user' => $user
                ]
            );

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like supprimé",
                'likes' => $likeRepository->count(['article' => $article])
            ], 200);
        }

        $like = new Like();

        $like->setArticle($article);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Like ajouté",
            'likes' => $likeRepository->count(['article' => $article])
        ], 200);
    }
}