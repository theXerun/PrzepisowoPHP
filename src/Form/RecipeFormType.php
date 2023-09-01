<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nazwa"
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
//                'prototype' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'attr' => [
                    'data-entry-add-label' => 'Dodaj',
                    'data-entry-remove-label' => 'Usuń',
                ],
                'entry_options' => [
                    'label' => false,
                ],
                'label' => 'Składniki'

            ])
            ->add('description', TextareaType::class,[
                'label' => 'Opis',
            ])
            ->add('isPublic', CheckboxType::class, [
                'required' => false,
                'label' => 'Czy chcesz udostępnić wszystkim ten przepis?'
            ])
            ->add('Zapisz', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
