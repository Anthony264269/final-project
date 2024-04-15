<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vehicule;
use App\Entity\User;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le garage de l'utilisateur connecté
        $myGarage = $user->getMyGarage(); // Assurez-vous que l'entité User a bien une méthode getMyGarage()

        // Si aucun garage n'est trouvé, retourner une réponse ou gérer la situation
        if (!$myGarage) {
            // Vous pourriez vouloir retourner une vue avec un message indiquant qu'aucun véhicule n'est disponible
            return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
                'vehicules' => [],
            ]);
        }

        // Récupérer les données des véhicules associés au garage de l'utilisateur
        $vehicules = $entityManager->getRepository(Vehicule::class)->findBy(['myGarage' => $myGarage]);

        // Passer les données au modèle Twig
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'vehicules' => $vehicules,
        ]);
    }
}
