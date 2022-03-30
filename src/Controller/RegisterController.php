<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManger){
        $this->entityManager=$entityManger;
    }
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user=new User();
        $form=$this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        $notification="";
        if ($form->isSubmitted() and $form->isValid()){
            $user=$form->getData();
            $search_email= $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$search_email){
                $password=$encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $mail = new Mail();
                $content="Bonjour ".$user->getFirstname().",<br/>"."Bienvenu et Merci de compter parmis nous sur le app-WebStore";
                $mail->send($user->getEmail(),$user->getFirstname(),'Bienvenue sur le app-webStore',$content);
                $notification ="Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte";
            }else $notification="l'email que vous avez renseigné existe déja";
        }

        return $this->render('register/index.html.twig',[
            'form'=>$form->createView(),
            'notification' => $notification
        ]);
    }
}
