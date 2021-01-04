<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categories;

class SearcheAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mots',SearchType::class,[
                "label"=>false,
                "attr"=>[
                    "class"=>"form-control",
                    "placeholder"=>"Entrez le mot de cle"
                ],
                'required'=>false
            ])
            ->add('categories', EntityType::class,[
                'class'=> Categories::class,
                'label'=>false,
                'attr'=> [
                    'class'=>"form-control"
                ],
                'required'=>false
            ])
            ->add('Recherche',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
