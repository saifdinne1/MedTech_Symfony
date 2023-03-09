<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Stripe\Checkout\Session;
use Stripe\Stripe; 
use Symfony\Component\Routing\Generator\UrlGeneratorInterface ; 

class PayementController extends AbstractController
{
    #[Route('/payement', name: 'app_payement')]
    public function index(): Response
    {
        return $this->render('payement/index.html.twig', [
            'controller_name' => 'PayementController',
        ]);
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        $stripeSK = "sk_test_51Kb2KGCAYowVw87d1Wd5zKjoRxjGECBWmhKAth9axa1tpLsxbzuCmhA0UDYb5jLm1t8xoipwJd0HJ5TYJnsYYAgj00VwaBW82a";
Stripe::setApiKey($stripeSK);
$session=Session::create([

    'payment_method_types'=>['card'],
    'line_items' => [[
        'price_data'=>[
            'currency'=>'usd',
            'product_data'=>[
                'name'=>'Commande'

            ],
            'unit_amount'=>2000
        ],
        'quantity'=>1,
    ]],
    'mode'=>'payment',
     'success_url'=> $this->generateUrl('success_url',[],
         UrlGeneratorInterface::ABSOLUTE_URL),
    'cancel_url'=> $this->generateUrl('cancel_url',[],
        UrlGeneratorInterface::ABSOLUTE_URL),
]);
return $this->redirect($session->url, 303);
    }
  
    
     #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('payement/success.html.twig', []);
    }
   
    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payement/cancel.html.twig', []);
    }

}
