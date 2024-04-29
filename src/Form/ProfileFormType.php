<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class)
        
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false, // Ne pas associer directement à l'attribut de l'entité
                'required' => false // Rendre le champ facultatif
            ])
            ->add('photo', FileType::class, [ // Ajoutez ce champ pour le téléchargement de photos
                'label' => 'Photo du profile',
                'mapped' => false, // Ne pas associer directement à l'attribut de l'entité
                'required' => false
            ]);
            // Ajoutez d'autres champs au besoin
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
