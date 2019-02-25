<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class)
            ->add('email', EmailType::class)
            ->add('status', HiddenType::class)
        ;

        switch($options['group']){
            case 'new':
                $builder
                    ->add('password', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'invalid_message' => 'user.password.must.match',
                        'first_options' => array('label' => 'Password'),
                        'second_options' => array('label' => 'Repeat Password'),
                    ))
                ;
                break;

            case 'edit':
                $builder
                    ->add('password', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'first_options' => array('label' => 'Change password'),
                        'second_options' => array('label' => 'Repeat Password'),
                    ))
                    ->add('firstname', TextType::class)
                    ->add('lastname', TextType::class)
                ;
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'group' => "new",
            'group' => "edit"
        ]);
    }
}
