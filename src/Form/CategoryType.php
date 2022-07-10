<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\CategoryGroup;
use App\Repository\CategoryGroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer un nom pour cette catégorie'
                    ])
                ]
            ])
            ->add('categoryGroup', EntityType::class, [
                'label' => 'Groupe',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois choisir au moins un groupe de catégories.'
                    ])
                ],
                'class' => CategoryGroup::class,
                'query_builder' => function (CategoryGroupRepository $categoryGroupRepository) {
                return $categoryGroupRepository->getQueryBuilderForCategoryEdition();
                },
                'choice_label' => 'label',
                'empty_data' => '',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisis un groupe de catégories',
                'attr' => [
                    'class' => 'custom-select-wanted'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-submit'
                ]
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => Category::class
        ]);
    }
}
