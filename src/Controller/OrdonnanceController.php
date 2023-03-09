<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\OrdonnanceType;
use App\Entity\Ordonnance;
use App\Repository\OrdonnanceRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Symfony\Component\HttpFoundation\Request;
$request = Request::createFromGlobals();


class OrdonnanceController extends AbstractController
{
   /**
     * @Route("/ordonnance", name="display_ordonnance")
     */
    public function index(): Response
    {
        $ordonnance = $this->getDoctrine()->getManager()->getRepository(Ordonnance::class)->findAll();
        return $this->render('ordonnance/index.html.twig', [
            'o'=>$ordonnance


        ]);
    }
    /**
     * @Route("/removeordo/{id}", name="supp_ordo")
     */
    public function suppressionordonnance(Ordonnance  $ordonnance): Response
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($ordonnance);
        $em->flush();
        $this->addFlash('danger','ordonnance Deleted successfully');

        return $this->redirectToRoute('display_ordonnance');


    }
 /**
 * @Route("/modifordonnance/{id}", name="modifordonnance")
 */
public function modifordonnance(Request $request, $id): Response
{
    $ordonnance = $this->getDoctrine()->getManager()->getRepository(Ordonnance::class)->find($id);

    $form = $this->createForm(OrdonnanceType::class, $ordonnance);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('success','ordonnance UPDATED successfully');

        return $this->redirectToRoute('display_ordonnance');
    }

    return $this->render('ordonnance/update.html.twig', [
        'ordon' => $form->createView(),
    ]);
}

/**
     * @Route("addO",  methods={"GET", "POST"})
     */
    public function Add(\Symfony\Component\HttpFoundation\Request $request)
    {
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ordonnance);
            $em->flush();
            $this->addFlash('success','ordonnance ADED successfully');

            return $this->redirectToRoute('display_ordonnance');

        }
        return $this->render('ordonnance/add.html.twig', [
            'form' => $form->createView()
        ]);

    }


/**
     * @Route("qrcode/{ido}",   name="qr_code")
     */
    public function showqr($ido)
{
    $ordonnance = $this->getDoctrine()
        ->getRepository(Ordonnance::class)
        ->find($ido);

    if (!$ordonnance) {
        throw $this->createNotFoundException('Service non trouvé pour l\'ID : ' . $ido);
    }

    // Créer un nouvel objet Builder
    $builder = new Builder();

    // Configurer les options du code QR avec le constructeur
    $qrCode = $builder->data($ordonnance->getDescription(),
    $ordonnance->getDate())
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(300)
        ->margin(10)
        ->build();

    // Rendre le template qrcode.html.twig avec le code QR en paramètre
    return $this->render('ordonnance/qrcode.html.twig', [
        'qrCodeData' => $qrCode->getDataUri(),
        'ordonnance' => $ordonnance,
    ]);
    
}

}