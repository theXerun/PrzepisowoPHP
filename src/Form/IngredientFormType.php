<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => IngredientType::class,
                'choice_label' => 'name',
                'placeholder' => 'Wybierz typ składnika',
                'label' => "Składnik",
            ])
            ->add('quantity', IntegerType::class, [
                'label' => "Ilość",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
