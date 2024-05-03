<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Forum;
use App\Entity\ForumCategory;
use App\Entity\Maintenance;
use App\Entity\MyGarage;
use App\Entity\User;
use App\Entity\Vehicule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
 

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Final Project');
    }

    public function configureMenuItems(): iterable
    {
     
        // yield MenuItem::linkToCrud('Category', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Comment', 'fa-solid fa-comments', Comment::class);
        yield MenuItem::linkToCrud('File', 'fa-solid fa-file', File::class);
        yield MenuItem::linkToCrud('Forum', 'fa-solid fa-user-group', Forum::class);
        yield MenuItem::linkToCrud('ForumCategory', 'fas fa-list', ForumCategory::class);
        yield MenuItem::linkToCrud('Maintenance', 'fa-solid fa-screwdriver-wrench', Maintenance::class);
        yield MenuItem::linkToCrud('MyGarage', 'fa-solid fa-warehouse', MyGarage::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Vehicule', 'fa-solid fa-car', Vehicule::class);

    }
}
