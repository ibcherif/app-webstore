<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManger;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManger=$entityManager;
    }
    #[Route('/mot-de-passe-oublie', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        if($this->getUser()) return $this->redirectToRoute('app_home');
        if($request->get('email')){
            $user = $this->entityManger->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user){
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTime());
                $this->entityManger->persist($resetPassword);
                $this->entityManger->flush();

                $url = $this->generateUrl('app_update_password', [
                    'token' => $resetPassword->getToken()
                ]);
                $content = "Bonjour ".$user->getFirstname()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site app-webStore <br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='$url'>mettre à jour votre mot de passe</a>";
                $mail =new Mail();
                $mail->send($user->getEmail(),$user->getFirstname().' '.$user->getLastname(),'Réinitialiser votre mot de passe sur le app-webStore',$content);

                $this->addFlash('notice','Vous allez recevoir dans quelques instants un mail de reinitialisation de mot de passe ');

            }else {
                $this->addFlash('notice','Cette adresse email est inconnue, merci de bien verifier votre Email');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'app_update_password')]
    public function update(Request $request,UserPasswordHasherInterface $hasher,$token): Response
    {

        $resetPassword = $this->entityManger->getRepository(ResetPassword::class)->findOneByToken($token);

        if(!$resetPassword) return $this->redirectToRoute('app_reset_password');

        if(new \DateTime() > $resetPassword->getCreatedAt()->modify('+ 3 hour')){
            $this->addFlash('notice','Votre demande de mot de passe a expiré. Merci de la renouveller');
            return $this->redirectToRoute('app_reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid()){
            $newPassword = $form->get('new_password')->getData();
            $hashPassword = $hasher->hashPassword($resetPassword->getUser(),$newPassword);
            $resetPassword->getUser()->setPassword($hashPassword);
            $this->entityManger->flush();
            $this->addFlash('notice','Votre mot de passe a bien été mis à jour');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig',[
            'form' => $form->createView()
        ]);


    }
}
