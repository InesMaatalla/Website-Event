<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label'=>false, 'required'=>true, 'attr' => [
                'class' => 'newInput',
                'class' => 'btn btn-secondary my-2 my-sm-0'
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campus::class,
        ]);
    }
}
