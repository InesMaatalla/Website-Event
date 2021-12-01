<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('rue')
            ->add('latitude')
            ->add('longitude')
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'required' => true,
                'label' => 'Ville',
                'placeholder' => 'Choisissez une ville',
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => array(
                    'class' => 'btn btn-light',
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
            'is_admin' => false,
            'embedded' => false,
        ]);
    }
}
