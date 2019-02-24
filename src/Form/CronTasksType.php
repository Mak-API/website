<?php

namespace App\Form;

use App\Entity\CronTasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CronTasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Add description name of your task',
            ])
            ->add('command', TextType::class, [
                'label' => 'Add command (example : "cache:clear")',
            ])
            ->add('options', TextType::class, [
                'label' => 'Add option(s) (example : "-v")',
                'required' => false,
            ])
            ->add('arguments', TextType::class, [
                'label' => 'Add argument(s) (example : "argument1 argument2")',
                'required' => false,
            ])
            ->add('expression', TextType::class, [
                'label' => 'Add cron expression (example, for every 5 minutes : "*/5 * * * *")',
            ])
            ->add('logFile', TextType::class, [
                'label' => 'Optional, add log file name without path',
                'required' => false,
            ])
            ->add('priority', IntegerType::class, [
                'data' => 1,
                'required' => false,
            ])
            ->add('disabled', CheckboxType::class, [
                'label' => 'Deactivate',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CronTasks::class,
        ]);
    }
}
