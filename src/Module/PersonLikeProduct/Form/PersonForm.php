<?php

namespace App\Module\PersonLikeProduct\Form;

use App\Component\Table\Extensions\FormType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class PersonForm extends FormType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('person_name', TextType::class, [
                'label' => 'Person',
                'disabled' => true
            ])
            ->add('person_id', HiddenType::class, [
                'required' => true,
            ])
            ->add('product_name', TextType::class, [
                'label' => 'Product',
                'attr' => [
                    'create-autocomplete' => "true",
                    'data-route' => $options['data_route']['product'],
                    'data-input-id' => 'person_form_product_id'
                ],
                'required' => false,

            ])
            ->add('product_id', HiddenType::class, [
                'required' => true,

            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Add',
                'attr' => [
                    'class' => 'btn btn-small btn-success'
                ]
            ]);
    }
}