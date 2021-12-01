<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;


class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//            ->add('roles', ChoiceType::class, [
//                'multiple' => true,
//                'expanded' => true,
//                'choices' => [
//                    'Rôle user' => true,
////                    'Out of Stock' => false,
//                ],
//            ])


        $builder->add('pseudo', TextType::class, [
            'label' => 'Pseudo : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('prenom', TextType::class, [
            'label' => 'Prénom : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('nom', TextType::class, [
            'label' => 'Nom : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('telephone', TelType::class, [
            'label' => 'Téléphone : ',
            'trim' => true,
            'required' => true,

        ]);

        $builder->add('email', EmailType::class, [
            'label' => 'Email : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            'required' => true,
            'first_options' => ['label' => 'Mot de passe : '],
            'second_options' => ['label' => 'Confirmation : '],
        ]);

        $builder->add('campus', EntityType::class, [
            'class' => Campus::class,
            'label' => 'Campus :',
            'choice_label' => 'nom',
        ]);

        $builder->add('imageFilename', FileType::class, [
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new Image()
            ],
            'label' => 'Ma photo :',
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => array(
                'class' => 'btn btn-outline-secondary',
            )
        ]);


        $builder->add('button', ButtonType::class, [
            'label' => 'Annuler',
            'attr' => array(
                'class' => 'btn btn-outline-secondary',
            )
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['monProfil'],
        ]);
    }

}
