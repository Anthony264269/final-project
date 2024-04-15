<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', null, [
                'label' => 'Marque',
                'attr' => ['class' => 'form-control']
            ])
            ->add('model', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un modèle.'])
                ]
            ])
            ->add('motorization');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
            'csrf_protection' => true, // Activer la protection CSRF
        ]);
    }
}
