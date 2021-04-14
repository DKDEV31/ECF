<?php

namespace App\Form;

use App\Entity\Benefit;
use App\Entity\Transfer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $accountId = $options['accountId'];
        $builder
            ->add('amount', MoneyType::class, [
                'row_attr' => ['class' => 'form-row'],
                'label' => 'Montant'
            ])
            ->add('benefit', EntityType::class, [
                'row_attr' => ['class' => 'form-select'],
                'label' => 'Beneficiaire',
                'class' => Benefit::class,
                'choice_label' => 'Name',
                'query_builder' => function(EntityRepository $er) use ($accountId){
                return $er->createQueryBuilder('b')
                    ->andWhere('b.Account = :accountId')
                    ->setParameter('accountId', $accountId)
                    ->orderBy('b.id');
                },
            ])
            ->add('type', ChoiceType::class, [
                'row_attr' => ['class' => 'form-select'],
                'label' => 'Type de virement',
                'choices' => ['Virement Interne' => 'Virement Interne', 'Virement Externe'=>'Virement Externe']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn-info'],
                'label' => 'Effectuer un virement'
            ])
            ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfer::class
        ]);
        $resolver->setRequired('accountId');
    }
}
