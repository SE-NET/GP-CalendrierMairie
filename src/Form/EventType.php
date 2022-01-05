<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'évènement'
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de l'événement"
            ])
            ->add('dateEnd', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
