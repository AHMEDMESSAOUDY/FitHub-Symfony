<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class Utilisateur1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('nom')
        ->add('prenom')
        ->add('adresse')
        ->add('telephone')
        ->add('date_de_naissance', DateType::class, [
           
          
            
            
            'years' => range(date('Y')-50, date('Y') - 15),
            'constraints' => [
               
            ],
        ])
      
    
      
        
      
    
        
    
        
    ;
        }

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Utilisateur::class,
    ]);
}
}