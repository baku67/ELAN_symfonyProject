<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateEmbauche', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('poste', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            // Selection parmies les entités entreprise
                ->add('entreprise', EntityType::class, [
                    'class' => Entreprise::class,
                    'choice_label' => 'raisonSociale',
                    'attr' => ['class' => 'form-control']
                ])
                // ->add('entreprise') //fonctionne aussi mais mieux d'être explicite
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
