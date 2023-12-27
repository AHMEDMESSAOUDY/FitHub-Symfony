<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range; 


class AdminTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('disponibilite', CheckboxType::class, [
            'label' => 'Availability',
            'required' => false,
            'value' => 1,
        ])
        ->add('prix', IntegerType::class, [
            'constraints' => [
                new NotBlank(),
                new Range(['min' => 0]),
            ],
        ])            ->add('nombreMax', IntegerType::class, [
                'label' => 'Maximum number of tickets',
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 1]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}