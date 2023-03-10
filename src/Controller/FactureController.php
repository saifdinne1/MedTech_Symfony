<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;






#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'facture_pdf', methods: ['GET'])]
    public function pdf(Facture $facture ,FactureRepository $factureRepository): Response
    {    
      
      $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('facture/pdffile.html.twig', [
            'facture' => $facture,
            
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A6', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);
    }


    #[Route('/email', name: 'app_facture_envoyer', methods: ['GET', 'POST'])]
    public function email( FactureRepository $factureRepository , MailerInterface $mailer): Response
     {
         $facture = new Facture();
         
              $email= (new TemplatedEmail())
             ->from('sdinne1@gmail.com')
             ->to('patient01@gamil.com')
             ->subject('votre facture MedTech')
             ->htmlTemplate('facture/email.html.twig')
              ->context(['facture' => $facture,]);
             $mailer -> send($email);
             return $this->redirectToRoute('app_facture_index', [ ], Response::HTTP_SEE_OTHER);
 }

    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, PaginatorInterface $paginator 
    , Request $request): Response
    {
        $facture = $factureRepository->findAll();
        $facture = $paginator->paginate(
            $facture, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4);  /*limit per page*/
        return $this->render('facture/index.html.twig', [
            'factures' => $facture,
        ]);


    } 


    #[Route('/recherche', name: 'recherche' )]
    function Recherche(FactureRepository $repository,  Request $request  , PaginatorInterface $paginator ){
        $data = $request->get('search') ;
        $date = new \DateTime($data);

        $facture = $repository->findBy(['date_facture'=>$date] );


        $facture = $paginator->paginate(
            $facture, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10);  /*limit per page*/
        return $this->render('facture/index.html.twig', [
            'factures' => $facture,
        ]);
    }

    #[Route('/recherche_nom', name: 'recherche_nom' )]
    function RechercheNom(FactureRepository $repository,  Request $request  , PaginatorInterface $paginator ){
        $nom = $request->get('search') ;
       

        $facture = $repository->findBy(['numero_facture'=>$nom] );


        $facture = $paginator->paginate(
            $facture, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10);  /*limit per page*/
        return $this->render('facture/index.html.twig', [
            'factures' => $facture,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository): Response
    {
        $date = new DateTime('today');
        $facture = new Facture();
        $facture->setDateFacture($date);
        
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);
            
            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

   
           

    

     
    
    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }



    
    
    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    } 

   



    

     
}
