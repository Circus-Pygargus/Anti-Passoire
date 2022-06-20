<?php

namespace App\Form;

use App\Entity\AntiPassoire;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAntiPassoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'required' => false,
                'label' => 'Catégorie',
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $monsterRepository) {
                    return $monsterRepository->createQueryBuilder('c')
                        ->orderBy('c.label', 'ASC');
                },
                'choice_label' => 'label',
                'empty_data' => '',
                'placeholder' => 'Toutes',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'custom-select-wanted'
                ]
            ])
            ->add('searchLimit', ChoiceType::class, [
                'required' => true,
                'label' => 'Max par page',
                'choices' => [
                    '2' => 2,
                    '10' => 10,
                    '25' => 25,
                    '50' => 50
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'custom-select-wanted'
                ]
            ])
            ->add('orderBy', ChoiceType::class, [
                'label' => 'Trier par',
                'required' => true,
                'choices' => AntiPassoire::ORDER_BY_POSSIBILITIES,
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'custom-select-wanted'
                ]
            ])
            ->add('orderDirection', ChoiceType::class, [
                'label' => 'Ordre',
                'required' => true,
                'choices' => AntiPassoire::ORDER_DIRECTION_POSSIBILITIES,
                'data' => AntiPassoire::ORDER_DIRECTION_POSSIBILITIES['Descendant'],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'custom-radio-wanted'
                ]
            ])
            ->add('pageNumber', HiddenType::class, [
                'required' => false,
                'data' => 1
            ])
            ->add('keyWords', SearchType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entre un ou plusieurs mots clés'
                ]
            ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
