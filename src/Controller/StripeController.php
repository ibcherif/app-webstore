<?php

namespace App\Controller;

use App\Class\Cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'app_stripe_create_session')]
    public function index(Cart $cart):Response
    {
        $product_for_stripe=[];
        $YOUR_DOMAIN = 'http://localhost:8000';
        foreach ($cart->getFull() as $product){
            $product_for_stripe[]=[
                'price_data' =>[
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN.'/upload/'.$product['product']->getIllustration()]
                    ]
                ],
                'quantity' =>$product['quantity']
            ];
        }

        Stripe::setApiKey('sk_test_51Kh33GBdFxDCblTfCtVlJdRr8COAwTwYtnW0z1liOIxpMePoVWFvI6lN4NUHwzc8ZWU2bVXBNFQOSSyQAX74vFgQ00HhZ3ruEC');
        $checkout_session = Session::create([
            'line_items' => [
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        return $this->redirect($checkout_session->url);

    }
}
