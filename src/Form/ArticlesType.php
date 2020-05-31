<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Entrez le titre de l'article"
                ]
            ])
            ->add('why', CKEditorType::class,[
                'label' => "Quel est le sujet de l'article ?",
                'attr' => [
                    'placeholder' => "Entrez le sujet"
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => "Description de l'article",
                'attr' => [
                    'placeholder' => "Entrez la description"
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => "Choisir l'image de l'article",
                'help' => "Cette image sera présente sur la miniature de l'article ainsi que sur la page de votre article (Taille max, 2Mo)",
                'attr' => [
                    'placeholder' => "Choisissez votre image"
                ]
            ])
            ->add('categories', EntityType::class, [
                'choice_label' => 'nom',
                'class' => Categories::class,
                'label' => 'Choisissez la (ou les) catégories associées à l\'article'        
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
