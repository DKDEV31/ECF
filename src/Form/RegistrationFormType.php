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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
              'label' => 'Prénom',
                'row_attr' =>['class' => 'form-row'],
                'constraints' => [
                    new Type([
                        'type' => 'string'
                    ])
                ],

            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [
                    new Type([
                        'type' => 'string'
                    ])
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'row_attr' => ['class' => 'form-email'],
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_name' => 'password',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'row_attr' => ['class' => 'form-password'],
                    'help' => 'Doit contenir au moins une majuscule, une minuscule, un chiffre et un symbole.
                Longueur minimum de 8 caracteres'
                ],
                'second_name' => 'confirmPassword',
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'row_attr' => ['class' => 'form-password']
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/[a-zA-Z]+[\d]+[\W]+/',
                        'match' => true
                    ]),
                    new Length([
                        'min' => 8,
                    ])
                ],
            ])
            ->add('adress', TextType::class,[
                'label' => 'Adresse',
                'mapped' => false,
                'row_attr' => ['class' => 'form-adress'],
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code Postal',
                'row_attr' => ['class' => 'form-zipcode'],
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'max' => 5
                    ]),
                    new Type([
                        'type' => 'string'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'row_attr' => ['class' => 'form-city'],
                'mapped' => false,
                'constraints' => [
                    new Type([
                        'type'=>'string'
                    ])
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Telephone',
                'row_attr' => ['class' => 'form-phone'],
                'constraints' => [
                    new Length([
                        'min'=>10
                    ])
                ]
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'view_timezone' => 'Europe/Paris',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'form-birthdate'],
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
