<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]
    public function index(EntityManagerInterface $entityManager,$reference):Response
    {
        $product_for_stripe=[];
        $YOUR_DOMAIN = 'http://localhost:8000';
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        foreach ($order->getOrderDetails()->getValues() as $product){
            $product_objet=$entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[]=[
                'price_data' =>[
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN.'/upload/'.$product_objet->getIllustration()]
                    ]
                ],
                'quantity' =>$product->getQuantity()
            ];
        }

        $product_for_stripe[]=[
            'price_data' =>[
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN]
                ]
            ],
            'quantity' => 1
        ];

        Stripe::setApiKey('sk_test_51Kh33GBdFxDCblTfCtVlJdRr8COAwTwYtnW0z1liOIxpMePoVWFvI6lN4NUHwzc8ZWU2bVXBNFQOSSyQAX74vFgQ00HhZ3ruEC');
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
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
