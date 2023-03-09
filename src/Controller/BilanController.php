<?php

namespace App\Controller;

use App\Entity\Bilan;
use App\Entity\Facture;
use App\Form\BilanType;
use App\Repository\FactureRepository;
use App\Repository\BilanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/bilan')]
class BilanController extends AbstractController
{
    #[Route('/', name: 'app_bilan_index', methods: ['GET'])]
    public function index(BilanRepository $bilanRepository, PaginatorInterface $paginator 
    , Request $request): Response
    {
        $bilan = $bilanRepository->findAll();
        $bilan = $paginator->paginate(
            $bilan, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4);

        return $this->render('bilan/index.html.twig', [
            'bilan' => $bilan,
        ]);
    }

    #[Route('/new', name: 'app_bilan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BilanRepository $bilanRepository, FactureRepository $factureRepository): Response
    {
        $somm = 0 ;
        $facture = new Facture();
        $bilan = new Bilan();
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);

       
        if ($form->isSubmitted() && $form->isValid()) {
        
            $bilanRepository->save($bilan, true);

            return $this->redirectToRoute('app_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bilan/new.html.twig', [
            'bilans' => $bilan,
            'form' => $form,
            
            'ventes' =>  $factureRepository->findAll(),
            'bil' =>  $bilanRepository->findAll(),

            ]);
    }

    #[Route('/{id}', name: 'app_bilan_show', methods: ['GET'])]
    public function show(Bilan $bilan, BilanRepository $bilanRepository, FactureRepository $factureRepository): Response
    {

        
     
        $somme = $this->getDoctrine()->getRepository(Facture::class)->findAll();
        $sum = 0;
        
   for ($i = 0; $i < count($somme); $i++) {
    if ( $somme[$i]->getDateFacture() > $bilan->getDateDebut()  or  $somme[$i]->getDateFacture() == $bilan->getDateDebut()){
        if ( $somme[$i]->getDateFacture() < $bilan->getDateFin()  or  $somme[$i]->getDateFacture() == $bilan->getDateFin()){
        $sum +=intval($somme[$i]->getMontant());
    }}}
       $somm = $sum-$bilan->getCahrge();  
        return $this->render('bilan/show.html.twig', [
            'bilan' => $bilan,
            // 'factures' => $facture,
            'factures' =>  $factureRepository->findAll(),
            'bil' =>  $bilanRepository->findAll(),
            'somm'=> $somm,
            'sum'=> $sum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bilan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bilan $bilan, BilanRepository $bilanRepository): Response
    {
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bilanRepository->save($bilan, true);

            return $this->redirectToRoute('app_bilan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bilan/edit.html.twig', [
            'bilan' => $bilan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bilan_delete', methods: ['POST'])]
    public function delete(Request $request, Bilan $bilan, BilanRepository $bilanRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bilan->getId(), $request->request->get('_token'))) {
            $bilanRepository->remove($bilan, true);
        }

        return $this->redirectToRoute('app_bilan_index', [], Response::HTTP_SEE_OTHER);
    }
}
