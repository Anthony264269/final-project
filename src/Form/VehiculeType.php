<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType; 

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
            ->add('motorization')
            
            ->add('photo', FileType::class, [ // Ajoutez ce champ pour le téléchargement de photos
                'label' => 'Photo du véhicule',
                'mapped' => false, // Ne pas associer directement à l'attribut de l'entité
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
            'csrf_protection' => true, // Activer la protection CSRF
        ]);
    }
}
