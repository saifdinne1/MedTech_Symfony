<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('dashboard.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/back', name: 'app_back')]
    public function back(): Response
    {
        return $this->render('back.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/test2', name: 'app_test2')]
    public function testt(): Response
    {
        return $this->render('test2.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/front', name: 'app_front')]
    public function front(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
