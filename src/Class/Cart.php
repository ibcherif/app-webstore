<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart{
    private RequestStack $session;

    public function __construct(RequestStack $session){
        $this->session=$session;
    }
    public function add($id){
        $cart=$this->session->getSession()->get('cart',[]);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else $cart[$id] =1;
        $this->session->getSession()->set('cart',$cart);
    }

    public function get(){
        return $this->session->getSession()->get('cart');
    }

    public function remove(){
        return $this->session->getSession()->remove('cart');
    }
}