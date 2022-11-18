<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'name',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez le nom du modèle']
            ])
            ->add('nbSeats',IntegerType::class, [
                'label' => 'Le nombre de sièges',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez un nombre']
            ])
            ->add('nbDoors',IntegerType::class, [
                'label' => 'Le nombre de portes',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez un nombre'],
            ])
            ->add('cost',NumberType::class, [
                'label' => 'Le prix',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez le prix en euros'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Choisissez la catégorie du véhicule',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => ['class' => 'btn btn-warning d-block mx-auto my-3 col-4']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'allow_file_upload' => true,
        ]);
    }
}
