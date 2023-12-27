<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank(['message' => 'email est obligatoire.']),
                new Email(['message' => 'email "{{ value }}" n\'est pas valide.']),
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez votre adresse email',
            ],
        ])
        ->add('password', PasswordType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez votre mot de passe',
            ],
            
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
