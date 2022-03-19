<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => 'Mon Email',
                'disabled' => true
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Mon Prénom',
                'disabled' => true
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Mon Nom',
                'disabled' => true
            ])
            ->add('old_password',PasswordType::class,[
                'label' =>'Mon Mot de Passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre Mot de Passe actuel'
                ]
            ])
            ->add('new_password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de Passe et la confirmation doivent être identique',
                'required' => true,
                'mapped' => false,
                'first_options' => [
                    'label' =>'Mon nouveau Mot de Passe','attr' => [
                        'placeholder' => 'Merci de saisir votre Mot de Passe actuel'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le Mot de Passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre Mot de Passe'
                    ]
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Mettre à jour'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
