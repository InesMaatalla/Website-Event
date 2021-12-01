<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AnnulerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie',
                'attr' => ['readonly' => true,]
            ] )
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date de la sortie',
                'widget' => 'single_text',
                'attr' => ['readonly' => true],
                    ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus',
                'choice_label' => 'nom',
                'attr' => ['readonly' => true],
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'label' => 'Lieu',
                'attr' => ['readonly' => true],
            ])
            ->add('motifAnnulation', TextareaType::class, [
                'label' => 'Motif',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => array(
                    'class' =>'btn btn-outline-secondary',
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
