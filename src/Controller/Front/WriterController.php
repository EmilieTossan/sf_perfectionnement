<?php

namespace App\Controller\Front;

use App\Repository\WriterRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WriterController extends AbstractController
{
    /**
     * @Route("writers", name="writer_list")
     */
    public function writerList(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render("front/writers.html.twig", ['writers' => $writers]);
    }

    /**
     * @Route("writer/{id}", name="show_writer")
     */
    public function showWriter($id, WriterRepository $writerRepository)
    {
        $writer = $writerRepository->find($id);

        return $this->render("front/writer.html.twig", ['writer' => $writer]);
    }

}