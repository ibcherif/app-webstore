<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Quel nom souhaitez-vous donner à votre adresse ?',
                'attr' =>[
                    'placeholder' => 'Nommer votre adresse'
                ]
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Votre Prénom',
                'attr' =>[
                    'placeholder' => 'Entrer votre Prénom'
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Votre Nom',
                'attr' =>[
                    'placeholder' => 'Entrer votre Nom'
                ]
            ])
            ->add('company', TextType::class,[
                'label' => 'Votre Société',
                'attr' =>[
                    'placeholder' => '(facultatif) Entrer le nom de votre Société'
                ]
            ])
            ->add('address', TextType::class,[
                'label' => 'Votre adresse',
                'attr' =>[
                    'placeholder' => '4 rue du Colonel Esperance',
                ]
            ])
            ->add('postal', TextType::class,[
                'label' => 'Votre Code Postal',
                'attr' =>[
                    'placeholder' => 'Entrer votre code Postal'
                ]
            ])
            ->add('city', TextType::class,[
                'label' => 'Votre Ville',
                'attr' =>[
                    'placeholder' => 'Entrer votre Ville'
                ]
            ])
            ->add('country', CountryType::class,[
                'label' => 'Quel nom souhaitez-vous donner à votre adresse ?',
                'attr' =>[
                    'placeholder' => 'Nommer votre adresse'
                ]
            ])
            ->add('phone', TelType::class,[
                'label' => 'Votre Téléphone',
                'attr' =>[
                    'placeholder' => 'Entrer votre Téléphone'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
