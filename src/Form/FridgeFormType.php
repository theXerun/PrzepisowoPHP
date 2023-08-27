<?php

namespace App\Form;

use App\Entity\Fridge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FridgeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'attr' => [
                    'data-entry-add-label' => 'Dodaj',
                    'data-entry-remove-label' => 'Usuń',
                ],
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'flex flex-row flex-wrap space-x-4',
                    ]
                ],
                'label' => false,
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
            'data_class' => Fridge::class,
        ]);
    }
}
