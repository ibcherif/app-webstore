<?php

namespace App\Class;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart{
    private RequestStack $session;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $session,EntityManagerInterface $entityManager){
        $this->session=$session;
        $this->entityManager=$entityManager;

    }
    public function add($id){
        $cart=$this->session->getSession()->get('cart',[]);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else $cart[$id] =1;
        $this->session->getSession()->set('cart',$cart);
    }
    public function decrease($id){
        $cart=$this->session->getSession()->get('cart',[]);
        if($cart[$id]>1){
            $cart[$id]--;
        }else unset($cart[$id]);
        $this->session->getSession()->set('cart',$cart);
    }
    public function get(){
        return $this->session->getSession()->get('cart');
    }

    public function remove(){
        return $this->session->getSession()->remove('cart');
    }

    public function delete($id){
        $cart=$this->session->getSession()->get('cart',[]);
        unset($cart[$id]);
        return $this->session->getSession()->set('cart',$cart);
    }

    public function getFull(){
        $dataCart=$this ->get();
        $cartComplete=[];
        if (!empty($dataCart)){
            foreach($dataCart as $id=>$quantity){
                $product=$this->entityManager->getRepository(Product::class)->findOneById($id);
                if(!$product) {
                    $this->delete($id);
                    continue;
                }
                $cartComplete[]=[
                    'product' =>$product,
                    'quantity' =>$quantity
                ];
            }
        }
        return $cartComplete;

    }
}