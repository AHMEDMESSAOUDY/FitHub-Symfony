<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            $builder
            ->add('nom', TextType::class, ['required' => false])
            ->add('typeConsultation', ChoiceType::class, [
                'label' => 'Choose the type of your appointment  :',
                'choices' => [
                    'A distance' => 'adistance',
                    'Cabinet' => 'cabinet',
                ],
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                ])
            ->add('dateConsultation', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'required' => false,]);
            // ->add('query', SearchType::class, [
            //     'label' => 'Search',
            //     'attr' => [
            //         'placeholder' => 'Enter search terms...'
            //     ]
            // ])
            // ->add('submit', SubmitType::class, [
            //     'label' => 'Search'
            // ]);
        }
    }

    

