<?php

namespace App\Module\Product\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'Info',
                'attr' => ['rows' =>5]
            ])
            ->add('public_date', DateType::class, [
                'label' => 'Public Date',
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr'  => [
                    'class' => 'btn btn-success btn-block'
                ]
            ]);
    }
}