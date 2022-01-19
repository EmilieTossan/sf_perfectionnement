<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
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

    public function adminCreateImage(
        Request $request,
        SluggerInterface $sluggerInterface,
        EntityManagerInterface $entityManagerInterface)
    {
        $image = new Image();

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if($imageForm->isSubmitted() && $imageForm->isValid()){

            $imageFile = $imageForm->get('src')->getData();

            if ($imageFile){

                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $image->setSrc($newFileName);
            }

            $entityManagerInterface->persist($image);
            
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_image_list");
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }

    public function adminUpdateImage(
        $id, 
        ImageRepository $imageRepository, 
        Request $request,
        SluggerInterface $sluggerInterface,
        EntityManagerInterface $entityManagerInterface)
    {
        $image = $imageRepository->find($id);

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if($imageForm->isSubmitted() && $imageForm->isValid()){

            $imageFile = $imageForm->get('src')->getData();

            if ($imageFile){

                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $image->setSrc($newFileName);
            }
            
            $entityManagerInterface->persist($image);
            
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_image_list");
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }

    public function adminDeleteImage(
        $id,
        ImageRepository $imageRepository,
        EntityManagerInterface $entityManagerInterface)
    {
        $image = $imageRepository->find($id);
        
        $entityManagerInterface->remove($image);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_image_list");
    }
}