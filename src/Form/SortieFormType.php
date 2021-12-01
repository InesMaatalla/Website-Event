<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom : ',
                'trim' => true,
                'required' => true,
                'attr' => ['placeholder' => 'Nom de votre sortie'],
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure :',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (min): ',
                'trim' => true,
                'required' => true,
                'attr' => ['min' => 0, 'placeholder' => 'Durée en minutes'],
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite : ',
                'widget' => 'single_text',

            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Participants : ',
                'trim' => true,
                'required' => true,
                'attr' => ['min' => 0],
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description :',
                'required' => true,
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus :',
                'choice_label' => 'nom',
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregister',
                'attr' => array(
                    'class' => 'btn btn-outline-secondary',
                )
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier',
                'attr' => array(
                    'class' => 'btn btn-outline-secondary',
                )

            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'required' => true,
                'label' => 'Lieu :',
                'placeholder' => 'Choisissez un lieu',
                'choice_label' => 'nom',
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'required' => true,
                'mapped' => false,
                'label' => 'Ville :',
                'placeholder' => 'Choisissez une ville',
                'choice_label' => 'nom',
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue :',
                'mapped' => false,
                'attr' => ['readonly' => true,]
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal :',
                'mapped' => false,
                'attr' => ['readonly' => true,
                ]
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude :',
                'mapped' => false,
                'attr' => ['readonly' => true,
                ]
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude :',
                'mapped' => false,
                'attr' => ['readonly' => true,
                ]
            ]);;

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
//        $builder->get('lieu')->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'embedded' => false,
        ]);
    }

    function onPreSetData(FormEvent $event)
    {
        $sortie = $event->getData();
        $form = $event->getForm();
        $data = $event->getData();

        $ville = $sortie->getLieu() ? $sortie->getLieu()->getVille() : null;

        $this->addElements($form, $ville);
    }

    protected function addElements(FormInterface $form, Ville $ville = null)
    {
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'required' => true,
            'mapped' => false,
            'label' => 'Ville :',
            'placeholder' => 'Choisissez une ville',
            'choice_label' => 'nom',
        ]);

//        $form->add('lieu', EntityType::class, [
//            'class' => Lieu::class,
//            'required' => true,
//            'label' => 'Lieu :',
//            'placeholder' => 'Choisissez un lieu',
//            'choice_label' => 'nom',
//        ]);

        $form->add('rue', TextType::class, [
            'label' => 'Rue :',
            'mapped' => false,
            'attr' => ['readonly' => true,]
        ]);
        $form->add('code_postal', TextType::class, [
            'label' => 'Code postal :',
            'mapped' => false,
            'attr' => ['readonly' => true,
            ]
        ]);
        $form->add('latitude', NumberType::class, [
            'label' => 'Latitude :',
            'mapped' => false,
            'attr' => ['readonly' => true,
            ]
        ]);
        $form->add('longitude', NumberType::class, [
            'label' => 'Longitude :',
            'mapped' => false,
            'attr' => ['readonly' => true,
            ]
        ]);
    }

    function onPostSubmit(FormEvent $event, EntityManagerInterface $entityManager)
    {
        $data = $event->getData();
        $form = $event->getForm();


        $this->addField($form->getParent(), $data);
    }

    protected function addField(FormInterface $form, ?Lieu $lieu)
    {
//        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
    }

}
