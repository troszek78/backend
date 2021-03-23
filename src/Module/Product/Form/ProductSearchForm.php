<?php

namespace App\Module\Product\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ProductSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => false,
            ])
            ->add('info', TextType::class, [
                'label' => 'Info',
                'required' => false,

            ])
            ->add('public_date', DateType::class, [
                'label' => 'Public Date',
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Search',
            ])
            ->add('reset', ButtonType::class, [
                'label' => 'Reset',
            ]);
    }
}