<?php

namespace App\Form;

use App\Entity\Ville;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label'=>false, 'required'=>true, 'attr' => [
                'class' => 'newInput',
                'class' => 'btn btn-secondary my-2 my-sm-0 d-flex-center w-75'
            ]])
            ->add('codePostal', TextType::class, ['label'=>false, 'required'=>true, 'attr' => [
                'class' => 'newInput',
                'class' => 'btn btn-secondary my-2 my-sm-0 d-flex-center w-75'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
