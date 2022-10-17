<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Imagick;
use App\Form\SuitType;
use App\Entity\Suit;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_first_part');
    }


    #[Route('/first-part', name: 'app_first_part')]
    public function firstPart(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Suit::class);

        # Create the 'suit' object and link it to the Symfony form
        $suit = new Suit();
        $form = $this->createForm(SuitType::class, $suit);
        $form->handleRequest($request);

        # The form is submitted and valid if the user defined a title, a description,
        # and uploaded a file with the JPG/PNG MIME type 
        if ($form->isSubmitted() && $form->isValid()) {
            
            # Get the file uploaded through the form, build a unique filename from original filename
            $suitFile = $form->get('suit')->getData();
            $originalFilename = $suitFile->getClientOriginalName();
            $newFilename = uniqid().'_'.$originalFilename;
            # Store the uploaded file with the unique filename on the webservers in the thumbnails directory,
            # which is simply /suits_thumbnails/
            try {
                $suitFile->move(
                    $this->getParameter('thumbnails_directory'),
                    $newFilename
                );
                $suit->setSuitFilename($newFilename);
            } catch (FileException $e) {
                return new Response("File exception");
            }
            # Store the 'suit' object in the database to display it in the application
            $entityManager->persist($suit);
            $entityManager->flush();
            return $this->redirectToRoute('app_first_part');
        }

        $allSuits = $repository->findAll();

        return $this->renderForm('home/homepage.html.twig', [
            'form' => $form,
            'allSuits' => $allSuits,
            'orig' => 'first-part'
        ]);
    }

    #[Route('/second-part', name: 'app_second_part')]
    public function secondPart(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Suit::class);

        # Create the 'suit' object and link it to the Symfony form
        $suit = new Suit();
        $form = $this->createForm(SuitType::class, $suit);
        $form->handleRequest($request);

        # The form is submitted and valid if the user defined a title, a description,
        # and uploaded a file with the JPG/PNG MIME type 
        if ($form->isSubmitted() && $form->isValid()) {
            
            # Get the file uploaded through the form
            $suitFile = $form->get('suit')->getData();

            # Build a unique filename from the original filename
            $originalFilename = $suitFile->getClientOriginalName();
            $newFilename = uniqid().'_'.$originalFilename;

            try {
                # Compress the uploaded PNG (level 9 of the gzip library) and save it
                $source = imagecreatefrompng($suitFile->getPathName());
                imagepng($source, $this->getParameter('thumbnails_directory').'/'.$newFilename, 9);
                $suit->setSuitFilename($newFilename);
            } catch (FileException $e) {
                return new Response("Exception in image processing");
            }
            # Store the 'suit' object in the database to display it in the application
            $entityManager->persist($suit);
            $entityManager->flush();
            return $this->redirectToRoute('app_second_part');
        }

        $allSuits = $repository->findAll();

        return $this->renderForm('home/homepage.html.twig', [
            'form' => $form,
            'allSuits' => $allSuits,
            'orig' => 'second-part'
        ]);
    }



    #[Route('/third-part', name: 'app_third_part')]
    public function thirdPart(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Suit::class);

        # Create the 'suit' object and link it to the Symfony form
        $suit = new Suit();
        $form = $this->createForm(SuitType::class, $suit);
        $form->handleRequest($request);

        # The form is submitted and valid if the user defined a title, a description,
        # and uploaded a file with the JPG/PNG MIME type 
        if ($form->isSubmitted() && $form->isValid()) {
            
            # Get the file uploaded through the form
            $suitFile = $form->get('suit')->getData();

            # Build a unique filename from original filename
            $originalFilename = $suitFile->getClientOriginalName();
            $newFilename = uniqid().'_'.$originalFilename;
            
            try {
                # Compress the uploaded PNG (level 9 of the gzip library), resize it and save it
                $filename = $suitFile->getPathName();
                list($width, $height) = getimagesize($filename);
                $source = imagecreatefrompng($filename);
                $thumb = imagecreatetruecolor(55, 55);
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, 55, 55, $width, $height);
                imagepng($thumb, $this->getParameter('thumbnails_directory').'/'.$newFilename);
                $suit->setSuitFilename($newFilename);
            } catch (FileException $e) {
                return new Response("Exception in image processing");
            }

            # Store the 'suit' object in the database to display it in the application
            $entityManager->persist($suit);
            $entityManager->flush();
            return $this->redirectToRoute('app_third_part');
        }

        $allSuits = $repository->findAll();

        return $this->renderForm('home/homepage.html.twig', [
            'form' => $form,
            'allSuits' => $allSuits,
            'orig' => 'third-part'
        ]);
    }

    #[Route('/fourth-part', name: 'app_fourth_part')]
    public function fourthPart(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Suit::class);

        $suit = new Suit();
        $form = $this->createForm(SuitType::class, $suit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            # Get the file uploaded through the form
            $suitFile = $form->get('suit')->getData();

            if ($suitFile) {
                $originalFilename = $suitFile->getClientOriginalName();
                $newFilename = uniqid().'_'.$originalFilename;

                try {
                    # Turn the file into a 100x100 thumbnail using Imagick
                    $filename = $suitFile->getPathName();
                    $imgck = new Imagick($filename);
                    $imgck->thumbnailImage(55, 55, true, true);
                    $imgck->writeImage($this->getParameter('thumbnails_directory')."/".$newFilename);
                    $suit->setSuitFilename($newFilename);
                } catch (Exception $e) {
                    return New Response("Exception in image processing");
                }
            }

            $entityManager->persist($suit);
            $entityManager->flush();
            return $this->redirectToRoute('app_fourth_part');
        }

        $allSuits = $repository->findAll();

        return $this->renderForm('home/homepage.html.twig', [
            'form' => $form,
            'allSuits' => $allSuits,
            'orig' => 'fourth-part'
        ]);
    }



    #[Route('/{origin}/delete/{id}', name:"suit_delete")]
    public function delete_suit(Request $request, ManagerRegistry $doctrine, int $id, string $origin)
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Suit::class);

        $to_delete = $repository->find($id);
        if (!$to_delete) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        
        unlink($this->getParameter('thumbnails_directory')."/".$to_delete->getSuitFilename());
        $entityManager->remove($to_delete);
        $entityManager->flush();
        if ($origin == "first-part") { $redirect = "app_first_part"; };
        if ($origin == "second-part") { $redirect = "app_second_part"; };
        if ($origin == "third-part") { $redirect = "app_third_part"; };
        if ($origin == "fourth-part") { $redirect = "app_fourth_part"; };

        return $this->redirectToRoute($redirect);
    }


}
