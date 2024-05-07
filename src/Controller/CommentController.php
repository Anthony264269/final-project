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
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/forum')] // Toutes les routes de ce contrôleur commenceront par /forum    

class CommentController extends AbstractController
{
    #[Route('/show/{id}', name: 'app_forum_show', methods: ['GET', 'POST'])]
    public function show(Forum $forum, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur correspondant à l'ID user_id du forum
        $user = $this->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newFile = $form->get('file')->getData();
            if ($newFile) {
                $originalFilename = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $newFile->guessExtension();
                try {
                    $newFile->move(
                        $this->getParameter('forum_directory'), // Assurez-vous que ce paramètre est bien défini dans config/services.yaml
                        $newFilename
                    );
                    // Associez le fichier à l'utilisateur
                    $photoFile = new File();
                    $photoFile->setUrl($newFilename);
                    $photoFile->setComment($comment);
                    $entityManager->persist($photoFile);
                    $comment->setFile($photoFile);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
                }
            }
            $comment->setForum($forum);
            $comment->setUser($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_forum_show', ['id' => $forum->getId()]);
        }
    
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
            'user' => $user,
            'form' => $form->createView(), // Passer le formulaire à Twig
        ]);
    }
}
