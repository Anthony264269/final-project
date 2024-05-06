<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Forum;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CommentController extends AbstractController
{
    #[Route('/forum/{id}', name: 'forum_show', methods: ['GET', 'POST'])]
    public function show(Forum $forum, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $newFile = $form->get('file')->getData();
            if ($newFile) {
                $originalFilename = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $newFile->guessExtension();
                try {
                    $newFile->move(
                        $this->getParameter('forum_directory'), // Make sure this parameter is defined in config/services.yaml
                        $newFilename
                    );

                    // Create a new File instance and associate it with the comment
                    $photoFile = new File();
                    $photoFile->setUrl($newFilename);
                    $entityManager->persist($photoFile);
                    $comment->setFile($photoFile);
                } catch (FileException $e) {
                    // Handle the error, for example, with a flash message
                    $this->addFlash('error', 'An error occurred while uploading the file.');
                }
            }

            // Set user and forum for the comment
            $comment->setUser($this->getUser());
            $comment->setForum($forum);

            // Persist the comment and associated changes
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirect to the forum page after adding the comment
            return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
        }

        // Render the forum page with the comment form
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }
}
