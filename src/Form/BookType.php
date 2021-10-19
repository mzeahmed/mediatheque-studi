<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookType extends AbstractType
{
    private GenreRepository $genres;

    public function __construct(GenreRepository $genres)
    {
        $this->genres = $genres;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre du livre',
                ],
                'required' => true,
            ])
            ->add('coverFile', FileType::class, [
                'required' => true,
                'label' => 'Image de couverture',
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Ecrivez une petite description du livre',
                ],
            ])
            ->add('author', TextType::class, [
                'attr' => [
                    'placeholder' => 'Qui est l\'auteur de ce livre',
                ],
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'label' => 'De quel genre est-il ?',
                'choice_label' => 'name',
                'choices' => $this->genres->findGenresByName(),
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('isReleasedAt', DateType::class, [
                'label' => 'Date de parution',
                'placeholder' => [
                    'day' => 'Jour',
                    'month' => 'Mois',
                    'year' => 'Année',
                ],
                'format' => 'dd-MM-yyyy',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
