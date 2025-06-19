<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class TaskForm
 * 
 * Formulaire Symfony pour gérer les tâches.
 * Permet de créer ou modifier une entité Task.
 */
class TaskForm extends AbstractType
{
    /**
     * Configure les champs du formulaire.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Options du formulaire
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')                             // Champ pour le titre de la tâche
            ->add('description', TextareaType::class)  // Champ pour la description de la tâche
        ;
    }

    /**
     * Configure les options du formulaire, notamment la classe de données associée.
     *
     * @param OptionsResolver $resolver Résolveur d’options du formulaire
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,  // Lie ce formulaire à l’entité Task
        ]);
    }
}
