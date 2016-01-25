<?php

namespace GenieeTest\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fruits
 *
 * @ORM\Table(name="fruits")
 * @ORM\Entity(repositoryClass="GenieeTest\Bundle\Repository\FruitsRepository")
 */
class Fruits
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $origin;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }


    public function setName($name){
        $this->name = $name;
    }

    public function setOrigin($origin){
        $this->origin = $origin;
    }
}

