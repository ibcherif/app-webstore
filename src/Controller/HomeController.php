<?php

namespace App\Controller;

use App\Class\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $mail=new Mail();
        $mail->send('badeke6404@nuesond.com','Naruto','Mon premier mail','Bonjour et Bienvenu sur app-webStore');
        return $this->render('home/index.html.twig');
    }
}
