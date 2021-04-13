<?php

namespace App\Form;

use App\Entity\RequestBenefit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BenefitAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
                'label' => 'Nom du Beneficiaire',
                'row_attr' => ['class' => 'form-row']
            ])
            ->add('BankName', TextType::class, [
                'label' => 'Nom de la banque',
                'row_attr' => ['class' => 'form-row']
            ])
            ->add('AccountNumber', TextType::class, [
                'label' => 'Numéro de compte',
                'row_attr' => ['class' => 'form-row']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn-info'],
                'label' => 'Demande de bénéficiare'
            ])
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequestBenefit::class
        ]);
    }
}
