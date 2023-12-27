<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;



class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email')
        ->add('nom')
        ->add('prenom')
        ->add('adresse')
        ->add('telephone')
        ->add('date_de_naissance', DateType::class, [
           
            'years' => range(date('Y')-50, date('Y') - 15),
            
        ])
    
        ->add('genre',ChoiceType::class, [
            'label' => 'Choisissez votre sexe ',
            'choices' => [
                'Merci de choisir votre sexe' => null,
                'Homme' => 'Homme',
                'Femme' => 'Femme',
                
                
            ],
            'multiple' => false,
            'expanded' => false,
        ])
        
        ->add('roles',ChoiceType::class, [
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'choices' => [
                'ROLE_MEMBRE' => 'ROLE_MEMBRE',
                'ROLE_NUTRISIONNISTE ' => 'ROLE_NUTRISIONNISTE ',
                'ROLE_COACH' => 'ROLE_COACH',
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                
            ],
        ])
    
        ->add('password', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
    
    
        
    ;
    $builder->get('roles')
    ->addModelTransformer(new CallbackTransformer(
        function ($rolesArray) {
            return count($rolesArray) ? $rolesArray[0] : null;
        },
        function ($rolesString) {
            return [$rolesString];
        }
    ));
}

      public function configureOptions(OptionsResolver $resolver): void
   {
    $resolver->setDefaults([
        'data_class' => Utilisateur::class,
    ]);
   }
}
