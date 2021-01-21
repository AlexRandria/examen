<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class VilleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('codePostal', TextType::class)
            ->add('population', IntegerType::class)
            ->add('slug', TextType::class)
            ->add('image', FileType::class, [
                'data_class' => null,
                'required' => false,
                'label' => 'Image produit',
                'attr' => [
                    'placeholder' => 'Placeholder Image produit',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de charger une jpg/png',
                        'uploadFormSizeErrorMessage' => 'Taille maximale de fichier 4 Méga'
                    ])
                ],
            ])
            ->add(
                'Departement',
                EntityType::class,
                [
                    'class' => Departement::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Choisir un Département',
                    'label' => 'Département',
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Ajouter Ville']
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
