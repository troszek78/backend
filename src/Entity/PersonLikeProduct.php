<?php

namespace App\Entity;

use App\Repository\PersonLikeProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonLikeProductRepository::class)
 */
class PersonLikeProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $person_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_id;

    /**
     * @var Person::class $person
     *
     * @ORM\ManyToOne(targetEntity=Person::class)
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @var Product::class $product
     *
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct(Person $person, Product $product)
    {
        $this->person_id = $person->getId();
        $this->product_id = $product->getId();
        $this->person = $person;
        $this->product = $product;

        return $this;
    }

    public function getPersonId(): int
    {
        return $this->person_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }
}
