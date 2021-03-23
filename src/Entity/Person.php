<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    public const STATE_ACTIVE = 1;
    public const STATE_BANNED = 2;
    public const STATE_REMOVED = 3;

    public const STATE_LABELS = [
        self::STATE_ACTIVE => 'Active',
        self::STATE_BANNED => 'Banned',
        self::STATE_REMOVED => 'Removed'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $l_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $f_name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=PersonLikeProduct::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private $personLikeProducts;

    public function __construct()
    {
        $this->personLikeProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getLName(): ?string
    {
        return $this->l_name;
    }

    public function setLName(string $l_name): self
    {
        $this->l_name = $l_name;

        return $this;
    }

    public function getFName(): ?string
    {
        return $this->f_name;
    }

    public function setFName(string $f_name): self
    {
        $this->f_name = $f_name;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStateLabel(): ?string
    {
        return self::STATE_LABELS[$this->state];
    }

    /**
     * @return Collection|PersonLikeProduct[]
     */
    public function getPersonLikeProducts(): Collection
    {
        return $this->personLikeProducts;
    }
}
