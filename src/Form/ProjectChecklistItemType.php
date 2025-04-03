<?php

namespace App\Form;

use App\Entity\ProjectChecklist;
use App\Entity\ProjectChecklistItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectChecklistItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom de l\'élément',
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                ],
                'label' => 'Libellé',
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('isChecked', CheckboxType::class, [
                'required' => false,
                'label' => 'Complété',
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => ['class' => 'mt-1 h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500']
            ])
            ->add('position', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                ],
                'label' => 'Position',
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'data' => 1 // Valeur par défaut
            ])
            ->add('relation', EntityType::class, [
                'class' => ProjectChecklist::class,
                'choice_label' => 'name',
                'required' => true,
                'placeholder' => 'Sélectionnez une checklist',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500'],
                'label' => 'Checklist parente',
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectChecklistItem::class,
        ]);
    }
}
