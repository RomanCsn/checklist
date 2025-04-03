<?php

namespace App\Form;

use App\Entity\ChecklistItemTemplate;
use App\Entity\ChecklistTemplate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChecklistItemTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('isRequired')
            ->add('position')
            ->add('relation', EntityType::class, [
                'class' => ChecklistTemplate::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChecklistItemTemplate::class,
        ]);
    }
}
