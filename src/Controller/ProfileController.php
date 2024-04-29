<?php
namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Entity\Vehicule; // Ajout de la classe Vehicule
use App\Form\ProfileFormType;
use App\Repository\VehiculeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager, Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        $user = $this->getUser();
       

        // Récupérer les véhicules associés à l'utilisateur
        $vehicules = $vehiculeRepository->findBy(['user' => $user]);

        

        

        // if ($profileForm->isSubmitted() && $profileForm->isValid()) {
        //     $photoFile = $profileForm->get('photo')->getData(); // Récupérez le fichier téléchargé à partir du formulaire.
        //     if ($photoFile) {
        //         $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
        //         $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        //         $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

        //         try {
        //             $photoFile->move(
        //                 $this->getParameter('user_directory'), // Assurez-vous de définir ce paramètre dans config/services.yaml
        //                 $newFilename
        //             );
                
        //             // Créez une nouvelle entité File et associez-la à l'utilisateur
        //             $file = new File();
        //             $file->setUrl($newFilename);
        //             $file->setUser($user); // Associez le fichier à l'utilisateur connecté
                
        //             $entityManager->persist($file);
        //         } catch (FileException $e) {
        //             // Gérer l'erreur en conséquence, par exemple, afficher un message flash
        //             $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
        //         }
                
        //     }
        //     /**
        //      * @var User $user
        //      */

        //     $user->setUpdatedAt(new DateTimeImmutable());
        //     $entityManager->persist($user);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_profile');
        // }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'vehicules' => $vehicules, // Passer les véhicules à afficher dans le modèle
        ]);
    }

    #[Route('/profile/edit/', name: 'app_profile_edit')]
    public function editProfile(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
     
        $photoFile = $user->getFile();


        // Créez un formulaire de modification du profil
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         
            // Encodez le mot de passe en clair si nécessaire
            $plainPassword = $form->get('plainPassword')->getData();
            $newFile = $form->get('photo')->getData();

            if ($newFile) {
                $originalFilename = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$newFile->guessExtension();

                try {
                    $newFile->move(
                        $this->getParameter('user_directory'), // Assurez-vous de définir ce paramètre dans config/services.yaml
                        $newFilename
                    );
                
                    // Créez une nouvelle entité File et associez-la à l'utilisateur
               
                    $photoFile->setUrl($newFilename);
                    $photoFile->setUser($user); // Associez le fichier à l'utilisateur connecté
                    $entityManager->persist($photoFile);
              

                    /**
                     * @var User $user
                     */
                    $user->setFile($photoFile);

                    
                 
                 
                } catch (FileException $e) {
                    // Gérer l'erreur en conséquence, par exemple, afficher un message flash
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
                }
                
            }
            /**
             * @var User $user
             */

            if ($plainPassword) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword($user, $plainPassword));
                }

             
            $entityManager->persist($user);
   
            $entityManager->flush();

            // Rediriger vers la page du profil après la modification
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('registration/edit_profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
