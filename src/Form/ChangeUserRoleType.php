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
            ->add('slug', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('wantedBiggestRole', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'USER' => 'Utilisateur',
                    'CONTRIBUTOR' => 'Contributeur'
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => ''
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
