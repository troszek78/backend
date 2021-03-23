<?php

namespace App\Module\Person\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
                'required' => false,
            ])
            ->add('l_name', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
            ])
            ->add('f_name', TextType::class, [
                'label' => 'First Name',
                'required' => false,
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'State',
                'choices' => [
                    '' => '',
                    'Active' => 1,
                    'Banned' => 2,
                    'Removed' => 3
                ],
                'required' => false,
            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Search',
            ])
            ->add('reset', ButtonType::class, [
                'label' => 'Reset',
            ]);
    }
}