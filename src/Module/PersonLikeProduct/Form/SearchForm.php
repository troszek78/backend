<?php

namespace App\Module\PersonLikeProduct\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('person_name', TextType::class, [
                'label' => 'Person',
                'required' => false,
            ])
            ->add('product_name', TextType::class, [
                'label' => 'Product',
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