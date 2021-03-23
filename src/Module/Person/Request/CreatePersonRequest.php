<?php

namespace App\Module\Person\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePersonRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="10")
     * @var string
     */
    public $login;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $l_name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="100")
     * @var string
     */
    public $f_name;

    /**
     * @Assert\NotBlank
     * @Assert\Range(min="1", max="3")
     * @var integer
     */
    public $state;
}