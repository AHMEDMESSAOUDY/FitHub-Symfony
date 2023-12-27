<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Utilisateur;
use Symfony\Component\Form\CallbackTransformer;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateConsultation')
            ->add('heureConsultation')
            ->add('typeConsultation', ChoiceType::class, [
                'label' => 'Choose the type of your appointment  :',
                'choices' => [
                    'Choose the type of your appointment' => null,
                    'A distance' => 'adistance',
                    'Cabinet' => 'cabinet',
                ],
                'multiple' => false,
                'expanded' => false,
            ])    
            ->add('nom')
        ->add('utilisateur');
        // ->add('utilisateur', EntityType::class, [
        //     'class' => Utilisateur::class,
        //     'label' => 'utilisateur',
        //     'query_builder' => function (EntityRepository $er) {
        //         return $er->createQueryBuilder('u')
        //             ->where('u.roles LIKE :role')
        //             ->setParameter('role', '%["ROLE_NUTRISIONNISTE"]%')
        //             ->orderBy('u.nom', 'ASC');
        //     },
        //     'choice_label' => function (Utilisateur $user) {
        //         return $user->getNom();
        //     },
        //     'multiple' => false,
        //     'expanded' => false,
        // ]);
        ;

       
    }

    // , EntityType::class, [
    //     'class' => User::class,
        
    //     'query_builder' => function (EntityRepository $er) {
    //         $a=$er->createQueryBuilder('u')
    //         ->where("u.roles = 'ROLE_NUTRISIONNISTE' ")
    //         ->orderBy('u.nom', 'ASC');
        
    //         return $a;
               
    //     },
    //     'choice_label' => 'nom',
    //     'multiple' => true,
    //     'expanded' => true,
    //     ]

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
