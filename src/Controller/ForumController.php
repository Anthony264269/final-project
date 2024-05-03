<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
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
                $newFilename = $safeFilename.'-'.uniqid().'.'.$newFile->guessExtension();
    
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
    public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
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
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
    }
}
