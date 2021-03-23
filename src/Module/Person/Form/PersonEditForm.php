<?php

namespace App\Module\Person\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
            ])
            ->add('l_name', TextType::class, [
                'label' => 'Last Name',
            ])
            ->add('f_name', TextType::class, [
                'label' => 'First Name',
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'State',
                'choices' => [
                    'Active' => 1,
                    'Banned' => 2,
                    'Removed' => 3
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr'  => [
                    'class' => 'btn btn-success btn-block'
                ]
            ]);
    }
}