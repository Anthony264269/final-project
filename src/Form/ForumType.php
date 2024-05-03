<?php

namespace App\Form;

use App\Entity\Forum;
use App\Entity\ForumCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet')
            ->add('message')
            ->add('forumCategory', EntityType::class, [
                'class' => ForumCategory::class,
                'choice_label' => 'category',
            ])
            ->add('file', FileType::class, [
                'label' => 'Image (JPG, PNG, GIF)',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
