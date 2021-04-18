<?php

namespace App\Form;

use App\Entity\RequestAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type de compte',
                'row_attr' => ['class' => 'form-select'],
                'choices' => [
                    'Compte courant' => 'Compte Courant',
                    'Compte epargne' => 'Compte epargne',
                    'Compte bourse' => 'Compte bourse'
                ]
            ])
            ->add('idCard', FileType::class, [
                'label' => 'Inserez une piece d\'identité valide',
                'row_attr' => ['class' => 'form-file'],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Demande de compte',
                'attr' => ['class' => 'btn-info']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequestAccount::class,
        ]);
    }
}
