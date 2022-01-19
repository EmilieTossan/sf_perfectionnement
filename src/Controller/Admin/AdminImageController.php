<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminImageController extends AbstractController
{
    public function adminImageList(ImageRepository $imageRepository)
    {
        $images = $imageRepository->findAll();

        return $this->render("admin/images.html.twig", ['images' => $images]);
    }

    public function adminShowImage($id, ImageRepository $imageRepository)
    {
        $image = $imageRepository->find($id);

        return $this->render("admin/image.html.twig", ['image' => $image]);
    }

    public function adminImageCreate(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $image = new Image();

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if($imageForm->isSubmitted() && $imageForm->isValid()){
            $entityManagerInterface->persist($image);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_image_list");
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }

    public function adminImageUpdate(
        $id, 
        ImageRepository $imageRepository, 
        Request $request, 
        EntityManagerInterface $entityManagerInterface)
    {
        $image = $imageRepository->find($id);

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if($imageForm->isSubmitted() && $imageForm->isValid()){
            $entityManagerInterface->persist($image);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_image_list");
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }

    public function adminImageDelete(
        $id,
        ImageRepository $imageRepository,
        EntityManagerInterface $entityManagerInterface)
    {
        $image = $imageRepository->find($id);
        
        $entityManagerInterface->persist($image);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_image_list");
    }
}