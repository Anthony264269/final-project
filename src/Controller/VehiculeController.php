<?php
namespace App\Controller;

use App\Entity\MyGarage;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            // Recherche d'un garage existant ou création d'un nouveau
            $existingGarage = $entityManager->getRepository(MyGarage::class)->findOneBy(['user' => $user]);

            if (!$existingGarage) {
                $existingGarage = new MyGarage();
                $existingGarage->setUser($user);
                $existingGarage->setUpdatedAt(new DateTimeImmutable());
                $entityManager->persist($existingGarage); // Persiste le nouveau garage uniquement si nécessaire
            }

            // Associez le garage existant ou le nouveau garage au véhicule
            $vehicule->setMyGarage($existingGarage);
            $entityManager->persist($vehicule);
            $entityManager->flush(); // Un seul flush suffit pour enregistrer toutes les modifications

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('vehicule/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
