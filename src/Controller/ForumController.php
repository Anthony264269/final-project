<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Forum;
use App\Form\CommentType;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum', methods: ['GET'])]
    public function index(ForumRepository $forumRepository): Response
    {
        return $this->render('forum/index.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
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
                    $photoFile->setForum($forum);
                    $entityManager->persist($photoFile);
                    $forum->addFile($photoFile);
                } catch (FileException $e) {
                    // Gérer l'erreur, par exemple, avec un message flash
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
                }
            }
            $forum->setCreatedAt(new \DateTimeImmutable());
            $forum->setUpdatedAt(new \DateTimeImmutable());
            $forum->setUser($user);
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forum_show', methods: ['GET'])]


    public function show(Forum $forum, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur correspondant à l'ID user_id du forum
        $user = $forum->getUser();
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
                    // Gérer l'erreur, par exemple, avec un message flash
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
    



    #[Route('/{id}/edit', name: 'app_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forum_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez si le jeton CSRF est valide
        if ($this->isCsrfTokenValid('delete' . $forum->getId(), $request->getPayload()->get('_token'))) {

            // Récupérez les fichiers associés au forum
            $files = $forum->getFile();

            // Supprimez les fichiers associés
            foreach ($files as $file) {
                $entityManager->remove($file);
            }

            // Supprimez le forum lui-même
            $entityManager->remove($forum);

            // Exécutez les opérations de suppression
            $entityManager->flush();
        }

        // Redirigez l'utilisateur vers une autre page après la suppression
        return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
    }

#[Route('/{id}/reply', name: 'app_forum_reply', methods: ['GET', 'POST'])]
public function reply(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
{
    if ($request->isMethod('POST')) {
        $user = $this->getUser();
        $commentContent = $request->request->get('comment');

        if (!empty($commentContent)) {
            $comment = new Comment();
            $comment->setMessage($commentContent);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($user);
            $comment->setForum($forum);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté.');

            return $this->redirectToRoute('app_forum_show', ['id' => $forum->getId()]);
        } else {
            $this->addFlash('error', 'Le commentaire ne peut pas être vide.');
        }
    }

    return $this->render('forum/show.html.twig', [
        'forum' => $forum
    ]);
}


    // src/Controller/ForumController.php

    // #[Route('/forum/{id}/add-comment', name: 'app_forum_add_comment', methods: ['POST'])]
    // public function addComment(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    // {
    //     $user = $this->getUser(); // Assurez-vous que l'utilisateur est connecté
    //     $commentContent = $request->request->get('comment');

    //     if ($commentContent) {
    //         $comment = new Comment();
    //         $comment->setMessage($commentContent);
    //         $comment->setUser($user);
    //         $comment->setForum($forum);

    //         $entityManager->persist($comment);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Votre commentaire a été ajouté.');
    //     } else {
    //         $this->addFlash('error', 'Le commentaire ne peut pas être vide.');
    //     }

    //     return $this->redirectToRoute('app_forum_show', ['id' => $forum->getId()]);
    // }
}
