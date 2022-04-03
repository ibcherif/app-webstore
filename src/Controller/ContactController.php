<?php

namespace App\Controller;

use App\Class\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid()){
            $mail =  new Mail();
            $mail->send(
                'narutoshippudenk2016@gmail.com',
                $form->get('prenom')->getData().' '.$form->get('nom')->getData(),
                "Message de contact d'un client",
                $form->get('content')->getData().' | Email : '.$form->get('email')->getData()
            );
            $this->addFlash('notice','Merci de nous avoir contacté. Notre équipe va vous répondre dans les meuilleurs delais');
        }
        return $this->render('contact/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
