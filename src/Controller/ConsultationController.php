<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ConsultationType;
use App\Entity\Consultation;
use App\Repository\ConsultationRepository;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Options;
use Dompdf\Dompdf;
$request = Request::createFromGlobals();

/**
     * @Route("/consultation")
     */
class ConsultationController extends AbstractController
{
   /**
     * @Route("/", name="display_consultation")
     */
    public function index(): Response
    {
        $consultations = $this->getDoctrine()->getRepository(Consultation::class)->findAll();

        // Filter out bad words in the description of each consultation
        foreach ($consultations as $consultation) {
            $this->badWord($consultation);
        }

        return $this->render('consultation/index.html.twig', [
            'e' => $consultations,
        ]);
    }

    private function badWord(Consultation $consultation): void
    {
        $badWords = ["méchant", "moche", "stupide", "laid"];

        $description = $consultation->getDiscription();

        foreach ($badWords as $badWord) {
            $description = str_ireplace($badWord, '**', $description);
        }

        $consultation->setDiscription($description);
    }

    
    /**
     * @Route("/removeconsult/{id}", name="supp_consult")
     */
    public function suppressionConsultation(Consultation  $consultation): Response
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($consultation);
        $em->flush();
        $this->addFlash('danger','Consultation Deleted successfully');

        return $this->redirectToRoute('display_consultation');


    }
 /**
 * @Route("/modifconsultation/{id}", name="modifconsultation")
 */
public function modifconsultation(Request $request, $id): Response
{
    $consultation = $this->getDoctrine()->getManager()->getRepository(Consultation::class)->find($id);

    $form = $this->createForm(ConsultationType::class, $consultation);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('success','Consultation UPDATED successfully');

        return $this->redirectToRoute('display_consultation');
    }

    return $this->render('consultation/update.html.twig', [
        'consult' => $form->createView(),
    ]);
}

    /**
     * @Route("/add",name="addconsultation",  methods={"GET", "POST"})
     */
    public function Add(\Symfony\Component\HttpFoundation\Request $request)
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($consultation);
            $em->flush();
            $this->addFlash('success','Consultation ADED successfully');


        }
        return $this->render('consultation/consultationF.html.twig', [
            'form' => $form->createView()
        ]);

    }
    

      /**
     * @Route("/DownloadProduitsData", name="DownloadProduitsData")
     */
    public function DownloadProduitsData(ConsultationRepository $repository)
    {
        $consultation=$repository->findAll();

        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        // On génère le html
        $html = $this->renderView('consultation/download.html.twig',
            ['consultation'=>$consultation ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'Tableau des consultation.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }
}