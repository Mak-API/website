<?php

namespace App\Form;

use App\Entity\CronTasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CronTasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('command')
            ->add('options')
            ->add('arguments')
            ->add('expression')
            ->add('lastExecution')
            ->add('lastReturnCode')
            ->add('logFile')
            ->add('priority')
            ->add('executeImmediately')
            ->add('disabled')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CronTasks::class,
        ]);
    }
}
