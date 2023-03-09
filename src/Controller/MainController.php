<?php

namespace App\Controller;

use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(StockRepository $stock): Response
    {
        $events = $stock->findAll();

        $rdvs = [];

       foreach($events as $event){
        $rdvs[] = [
            'id' => $event->getId(),
            'titre' => $event->getTitre(),
            'type' => $event->getType(),
            'date' => $event->getDate()->format('Y-m-d H:i:s'),
            'fournisseur' => $event->getFournisseur(),

        ];
       }

       $data =json_encode($rdvs);

        return $this->render('main/index.html.twig', compact('data'));
    }
}