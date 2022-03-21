<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager=$entityManager;
    }
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        $dataCart=$cart->get();
        $cartComplete=[];
        if (!empty($dataCart)){
            foreach($dataCart as $id=>$quantity){
                $cartComplete[]=[
                    'product' =>$this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity' =>$quantity
                ];
            }
        }

        return $this->render('cart/index.html.twig',[
            'cart'=>$cartComplete
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart,$id){
        $cart->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'remove_to_cart')]
    public function remove(Cart $cart){
        $cart->remove();
        return $this->redirectToRoute('app_cart');
    }
}
