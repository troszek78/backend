<?php

namespace App\Module\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="255")
     * @var string
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min="2")
     * @var string
     */
    public $info;

    /**
     * @Assert\NotBlank
     * @var \DateTime
     */
    public $public_date;
}