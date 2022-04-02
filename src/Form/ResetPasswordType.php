<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de Passe et la confirmation doivent être identique',
                'required' => true,
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
                'label' => 'Mettre à jour mon mot de passe',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
