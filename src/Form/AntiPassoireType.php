<?php

namespace App\Form;

use App\Entity\AntiPassoire;
use App\Entity\Category;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AntiPassoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer un titre pour cet anti passoire.'
                    ])
                ]
            ])
            ->add('seoKeywords', TextType::class, [
                'label' => 'Mots clés (pour les metadatas, séparés par une virgule)',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois entrer au moins un mot clé pour cet anti passoire.'
                    ])
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois choisir au moins une catégorie.'
                    ])
                ],
                'class' => Category::class,
                'choice_label' => 'label',
//                'disabled' => true, // already completed, will stay the same even if user tries to change it
                'empty_data' => '',
                'expanded' => false,
                'multiple' => true,
                'placeholder' => 'Choisis une catégorie',
                'attr' => [
                    'class' => 'custom-select-wanted'
                ]
            ])
            ->add('isPublished', ChoiceType::class, [
                'required' => true,
                'label' => 'Publié',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'attr' => [
                    'class' => 'custom-select-wanted'
                ]
            ])
            ->add('text', CKEditorType::class, [
//                'config_name' => 'my_config',
                'label' => 'Texte',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tu dois choisir au moins une catégorie.'
                    ])
                ],
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
            'data-class' => AntiPassoire::class
        ]);
    }
}
