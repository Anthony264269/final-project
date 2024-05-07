<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Forum;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('message')
  
 
    ->add('file', FileType::class, [
        'label' => 'Ajouter une photo (JPG, PNG, GIF)',
        'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité
        'required' => false, // Le champ n'est pas requis
    ])

  
  ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
