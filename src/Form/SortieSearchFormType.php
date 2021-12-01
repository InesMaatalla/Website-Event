<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'Sortie : ',
                'trim' => true,
                'required' => false,
                'mapped' => false,
                'attr' => ['placeholder' => 'Le nom de la sortie contient']
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus :',
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Entre ',
                'mapped' => false,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Et ',
                'mapped' => false,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('isOrganisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'mapped' => false,
                'required' => false,
            ])
            ->add('isInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'mapped' => false,
                'required' => false,
            ])
            ->add('isNotInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'mapped' => false,
                'required' => false,
            ])
            ->add('passee', CheckboxType::class, [
                'label' => 'Sorties passées',
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => array(
                    'class' => 'btn btn-outline-secondary ',
                    'style' => 'width: 150px',
                ),
            ])
            ->add('reset', SubmitType::class, [
                'label' => 'Défiltrer',
                'attr' => array(
                    'class' => 'btn btn-outline-secondary ',
                    'style' => 'width: 150px',
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
