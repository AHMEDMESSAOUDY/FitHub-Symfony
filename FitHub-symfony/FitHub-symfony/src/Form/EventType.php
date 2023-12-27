<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Validator\Constraints\File;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomEvent', TextType::class, [
            'label' => 'Event Name',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter the event name',
                ]),
                new Length([
                    'min' => 5,
                    'max' => 50,
                    'minMessage' => 'The event name must be at least {{ limit }} characters long',
                    'maxMessage' => 'The event name cannot be longer than {{ limit }} characters',
                ]),
            ],
            'attr' => [
                'placeholder' => 'Enter the name of the event',
            ],
        ])
        ->add('description', TextType::class, [
            'label' => 'Description',
            'required' => false,
            'constraints' => [
                new Length([
                    'min' => 10,
                    'max' => 255,
                    'minMessage' => 'The description must be at least {{ limit }} characters long',
                    'maxMessage' => 'The description cannot be longer than {{ limit }} characters',
                ]),
            ],
            'attr' => [
                'placeholder' => 'Enter a description of the event',
            ],
        ])
            ->add('type', ChoiceType::class, [
                'label' => 'Event Type',
                'choices' => [
                    'Competition' => 'Competition',
                    'E-sports' => 'E-sports',
                ],
                'attr' => [
                    'placeholder' => 'Select the type of the event',
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the event location',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 50,
                        'minMessage' => 'The event location must be at least {{ limit }} characters long',
                        'maxMessage' => 'The event location cannot be longer than {{ limit }} characters',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Enter the location of the event',
                ],
            ])
            ->add('date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Select the date of the event',
                ],
                'data' => new \DateTime(),

            ])
            
            ->add('type', ChoiceType::class, [
                'label' => 'Event Type',
                'choices' => [
                    'Competition' => 'Competition',
                    'E-sports' => 'E-sports',
                ],
                'attr' => [
                    'placeholder' => 'Select the type of the event',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Event Image',
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',

                        ],
                        'mimeTypesMessage' => 'please upload an image file' ,

                    ])
                    ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}