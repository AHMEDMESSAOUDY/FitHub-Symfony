<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new NotBlank([
                    'message' => 'please enter your name',
                ]),
                new Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'your name must be at least {{ limit }} caracters',
                    'maxMessage' => 'your name cant have more than {{ limit }} caracters',
                ]),
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'constraints' => [
                new NotBlank([
                    'message' => 'please enter your mail',
                ]),
                new Email([
                    'message' => 'please enter a valid mail address',
                ]),
            ],
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}