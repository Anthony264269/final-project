<?php
namespace App\Controller;

use App\Entity\MyGarage;
use App\Entity\Vehicule;
use App\Entity\File; // Assurez-vous d'inclure l'entité File.
use App\Form\VehiculeType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VehiculeController extends AbstractController
{
    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $vehicule = new Vehicule();
        $user = $this->getUser(); // Assurez-vous que l'utilisateur est connecté.
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData(); // Récupérez le fichier téléchargé à partir du formulaire.
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('vehicule_directory'), // Assurez-vous de définir ce paramètre dans config/services.yaml
                        $newFilename
                    );
                    // Créez une nouvelle entité File et associez-la à l'utilisateur
                    $file = new File();
                    $file->setUrl($newFilename);
                    $file->setUser($user); // Associez le fichier à l'utilisateur connecté

                    $entityManager->persist($file);
                } catch (FileException $e) {
            
                }
            }

            // Recherche d'un garage existant ou création d'un nouveau
            $existingGarage = $entityManager->getRepository(MyGarage::class)->findOneBy(['user' => $user]);

            if (!$existingGarage) {
                $existingGarage = new MyGarage();
                $existingGarage->setUser($user);
                $existingGarage->setUpdatedAt(new DateTimeImmutable());
                $entityManager->persist($existingGarage);
            }

            // Associez le garage existant ou le nouveau garage au véhicule
            $vehicule->setMyGarage($existingGarage);
            $vehicule->setImageUrl($file);
            $entityManager->persist($vehicule);
            $entityManager->flush();

            $vehicule->setUser($user);

            $entityManager->persist($vehicule);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('vehicule/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
