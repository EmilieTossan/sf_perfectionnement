<?php

namespace App\Controller\Admin;

use App\Entity\Writer;
use App\Form\WriterType;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminWriterController extends AbstractController
{
    /**
     * @Route("admin/writers", name="admin_writer_list")
     */
    public function adminWriterList(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render("admin/writers.html.twig", ['writers' => $writers]);
    }

    /**
     * @Route("admin/writer/{id}", name="admin_show_writer")
     */
    public function adminShowWriter($id, WriterRepository $writerRepository)
    {
        $writer = $writerRepository->find($id);

        return $this->render("admin/writer.html.twig", ['writer' => $writer]);
    }

    /**
     * @Route("admin/create/writer", name="admin_create_writer")
     */
    public function adminWriterCreate(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $writer = new Writer();

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if($writerForm->isSubmitted() && $writerForm->isValid()){
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_writer_list");
        }

        return $this->render("admin/writerform.html.twig", ['writerForm' => $writerForm->createView()]);
    }

    /**
     * @Route("admin/update/writer", name="admin_update_writer")
     */
    public function adminWriterUpdate(
        $id, 
        WriterRepository $writerRepository, 
        Request $request, 
        EntityManagerInterface $entityManagerInterface)
    {
        $writer = $writerRepository->find($id);

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if($writerForm->isSubmitted() && $writerForm->isValid()){
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_writer_list");
        }

        return $this->render("admin/writerform.html.twig", ['writerForm' => $writerForm->createView()]);
    }

    /**
     * @Route("admin/delete/writer", name="admin_delete_writer")
     */
    public function adminWriterDelete(
        $id,
        WriterRepository $writerRepository,
        EntityManagerInterface $entityManagerInterface)
    {
        $writer = $writerRepository->find($id);
        
        $entityManagerInterface->persist($writer);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_writer_list");
    }
}