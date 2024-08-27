<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Video;
use App\Entity\VideoProgress;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

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
        // return $this->render('some/path/my-dashboard.html.twig');
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('VideoPlatform')
            ->renderContentMaximized();
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new \Exception('Mauvais utilisateur.');
        }

        $image = 'uploads/img/users/' . $user->getImage();

        return parent::configureUserMenu($user)
            ->setAvatarUrl($image);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Visiter le site', 'fas fa-home', 'app_home');
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-cog');


        // -------------------------------------
        // VIDEOS
        // -------------------------------------
        yield MenuItem::section('Vidéos');
        yield MenuItem::linkToCrud('Vidéos', 'fas fa-video', Video::class);
        yield MenuItem::linkToCrud('Progression', 'fas fa-tasks', VideoProgress::class);

        // -------------------------------------
        // PARAMETRES
        // -------------------------------------
        yield MenuItem::section('Paramètres du site');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        // yield MenuItem::linkToCrud('Configuration du site', 'fa fa-cogs', Setting::class);
    }
}
