<?php

namespace App\Form;

use App\Entity\Fiche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descriptionFiche')
            ->add('consultation')
            ->add('nomFiche')
            ->add('category', ChoiceType::class, [
                'label' => 'Choose the category of your fiche  :',
                'choices' => [
                    'Choose the category of your fiche ' => null,
                    'New' => 'new',
                    'Follow up' => 'followup',
                ],
                'multiple' => false,
                'expanded' => false,
            ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
}
