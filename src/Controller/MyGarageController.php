<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Entity\Vehicule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;

class MyGarageController extends AbstractController
{
    #[Route('/my-garage/{id}', name: 'app_my_garage')]
    public function index(int $id, VehiculeRepository $vehiculeRepository): Response
    {
        $vehicule = $vehiculeRepository->find($id);

        if (!$vehicule) {
            throw $this->createNotFoundException('Véhicule non trouvé');
        }

        $garage = $vehicule->getMyGarage();
        // dd($garage);
        if (!$garage) {
            // Gestion lorsque le garage n'est pas trouvé
            // Redirigez vers une page d'erreur ou retournez une réponse différente
            return $this->render('error/no_garage.html.twig', [
                'message' => 'Aucun garage trouvé pour ce véhicule. Veuillez contacter l\'administrateur.'
            ]);
        }
        $garageId = $garage->getId();

        $vehicleIds = $vehiculeRepository->getVehicleIdsByGarage($garageId);
        $currentKey = array_search($id, $vehicleIds);

        $nextId = isset($vehicleIds[$currentKey + 1]) ? $vehicleIds[$currentKey + 1] : $vehicleIds[0];
        $prevId = isset($vehicleIds[$currentKey - 1]) ? $vehicleIds[$currentKey - 1] : $vehicleIds[array_key_last($vehicleIds)];

        return $this->render('myGarage/index.html.twig', [
            'vehicule' => $vehicule,
            'nextId' => $nextId,
            'prevId' => $prevId,
        ]);
    }


    #[Route('/my-garage/maintenance/add/{vehicleId}', name: 'add_maintenance', methods: ['POST'])]
    public function addMaintenance(Request $request, VehiculeRepository $vehiculeRepository, EntityManagerInterface $entityManager, int $vehicleId): Response
    {
        $vehicule = $vehiculeRepository->find($vehicleId);
        if (!$vehicule) {
            throw $this->createNotFoundException('Véhicule non trouvé');
        }

        $maintenance = new Maintenance();
        $maintenance->setMaintenance($request->request->get('description'));

        // Utilisez DateTimeImmutable directement si possible
        $immutableDate = new \DateTimeImmutable(); // Capture la date et l'heure actuelles
        $maintenance->setCreatedAt($immutableDate);
        $maintenance->setVehicule($vehicule);

        $entityManager->persist($maintenance);
        $entityManager->flush();

        // Redirigez vers la vue du véhicule après l'ajout de la maintenance
        return $this->redirectToRoute('app_my_garage', ['id' => $vehicleId]);
    }

    #[Route('/maintenance/delete/{id}', name: 'delete_maintenance', methods: ['POST'])]
    public function deleteMaintenance(EntityManagerInterface $entityManager, int $id): Response
    {
        $maintenance = $entityManager->getRepository(Maintenance::class)->find($id);
        if (!$maintenance) {
            throw $this->createNotFoundException('Maintenance non trouvée');
        }

        // Récupérer l'ID du véhicule associé avant de le supprimer
        $vehicleId = $maintenance->getVehicule()->getId();

        $entityManager->remove($maintenance);
        $entityManager->flush();

        // Rediriger l'utilisateur vers une route appropriée après la suppression
        return $this->redirectToRoute('app_my_garage', ['id' => $vehicleId]);
    }


}
