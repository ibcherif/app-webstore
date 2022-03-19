<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function  __construct(EntityManagerInterface $entityManager){
        $this->entityManager=$entityManager;
    }
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user=$this->getUser();
        $form=$this->createForm(ChangePasswordType::class,$user);
        $form->handleRequest($request);
        $notif=null;
        if ($form->isSubmitted() and $form->isValid()){
            $old_pass=$form->get('old_password')->getData();
            if($hasher->isPasswordValid($user,$old_pass)){
                $new_pass=$form->get('new_password')->getData();
                $has_pass=$hasher->hashPassword($user,$new_pass);
                $user->setPassword($has_pass);
                //$this->entityManager->persist($user); // facultatif
                $this->entityManager->flush();
                $notif="Votre mot de passe a bien été mis à jour";
            }else $notif="Votre mot de passe actuel est incorretct";
        }
        return $this->render('account/password.html.twig',[
            'form' => $form->createView(),
            'notif' => $notif
        ]);
    }
}
