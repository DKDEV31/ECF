<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
              'label' => 'Prénom',
                'row_attr' =>['class' => 'form-row'],
                'constraints' => [

                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [

                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'row_attr' => ['class' => 'form-email'],
                'constraints' => [

                ]
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_name' => 'password',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'row_attr' => ['class' => 'form-password']
                ],
                'second_name' => 'confirmPassword',
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'row_attr' => ['class' => 'form-password']
                ],

                'constraints' => [

                ]
            ])
            ->add('adress', TextType::class,[
                'label' => 'Adresse',
                'mapped' => false,
                'row_attr' => ['class' => 'form-adress'],
                'constraints' => [

                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code Postal',
                'row_attr' => ['class' => 'form-zipcode'],
                'mapped' => false,
                'constraints' => [

                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'row_attr' => ['class' => 'form-city'],
                'mapped' => false,
                'constraints' => [

                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Telephone',
                'row_attr' => ['class' => 'form-phone'],
                'constraints' => [

                ]
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'view_timezone' => 'Europe/Paris',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'form-birthdate'],
                'constraints' => [

                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => ['class' => 'btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
