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
            ->add('name', TextType::class, [
                'label' => 'Nom de votre adresse*',
                'attr' => [
                    'placeholder' => "Entrer le nom de l'adresse"
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom*',
                'attr' => [
                    'placeholder' => "Entrer votre prénom"
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom*',
                'attr' => [
                    'placeholder' => "Entrer votre nom"
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre société',
                'required' =>false,
                'attr' => [
                    'placeholder' => "Entrer le nom de votre société"
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse*',
                'attr' => [
                    'placeholder' => "Entrer votre adresse"
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal*',
                'attr' => [
                    'placeholder' => "Entrer votre code postal"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville*',
                'attr' => [
                    'placeholder' => "Entrer votre ville"
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays*',
                'attr' => [
                    'placeholder' => "Entrer votre pays"
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre téléphone*',
                'attr' => [
                    'placeholder' => "Entrer votre numero de téléphone"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon adresse'
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
