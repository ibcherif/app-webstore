<?php

namespace App\Controller;

use App\Class\Cart;
use App\Class\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager=$entityManager;
    }
    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_success')]
    public function index(Cart $cart,$stripeSessionId): Response
    {
        $order =$this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if(!$order || $order->getUser()!=$this->getUser()) return $this->redirectToRoute('app_home');
        if(!$order->getIsPaid()){
            $cart->remove();
            $order->setIsPaid(1);
            $this->entityManager->flush();
            $mail = new Mail();
            $content="Bonjour ".$order->getUser()->getFirstname().",<br/>"."Merci pour votre commande sur le app-WebStore";
            $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(),'Votre commande sur  le app-webStore est bien validé',$content);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
