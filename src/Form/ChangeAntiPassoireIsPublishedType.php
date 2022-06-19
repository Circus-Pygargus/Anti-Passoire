<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeAntiPassoireIsPublishedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('slug', TextType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'class' => 'd-none'
            ]
        ])
        ->add('isPublished', ChoiceType::class, [
            'required' => false,
            'label' => false,
            'choices' => [
                'Publié' => true,
                'Caché' => false
            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'class' => 'd-none'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
