<?php

namespace App\Module\PersonLikeProduct\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest
{
    /**
     * @Assert\NotBlank(message="Person not set!")
     * @Assert\Type(type="numeric")
     * @Assert\GreaterThan(0)
     * @var int
     */
    public $person_id;

    /**
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $person_name;

    /**
     * @Assert\NotBlank(message="Product not set!")
     * @Assert\Type(type="numeric")
     * @Assert\GreaterThan(0)
     * @var int
     */
    public $product_id;

    /**
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $product_name;
}