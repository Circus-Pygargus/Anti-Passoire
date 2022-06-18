<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeUserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('wantedBiggestRole', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Utilisateur' => 'USER',
                    'Contributeur' => 'CONTRIBUTOR'
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'several-custom-for-one-wanted d-none'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
