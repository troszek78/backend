<?php

namespace App\Component\Table\Extensions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FormType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver) {

        $resolver->setDefaults(array(
            'data_route' => null, //Set default to null in case param is not passed
        ));
    }
}