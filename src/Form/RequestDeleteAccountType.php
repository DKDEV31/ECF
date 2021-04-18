<?php

namespace App\Form;

use App\Entity\RequestAccount;
use App\Entity\RequestDelete;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RequestDeleteAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('signature', FileType::class, [
                'label' => 'Demande de cloture manuscrite',
                'row_attr' => ['class'=>'form-file'],
                'mapped' => false,
                'constraints' =>[
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg'
                        ]
                    ])
                ],
                'help' => 'Maximum 2 Mo, seulement .jpeg, .jpg et .pdf'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Demande de cloture',
                'attr' => ['class' => 'btn-info']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequestDelete::class,
        ]);
    }
}
