<?php

namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="basics")
 */
class Basics
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="color", type="string", length=256) */
    protected $color;

    /** @ORM\Column(name="categories", type="text", nullable=true) */
    protected $categories;

    /** @ORM\Column(name="subtitle", type="string", nullable=false) */
    protected $subtitle;

    /**
     * @param int $id
     * @return Basics $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $categories
     * @return Basics $this
     */
    public function setCategories($categories)
    {
        $this->categories = json_encode($categories);
        return $this;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return json_decode($this->categories);
    }

    /**
     * @param string $color
     * @return Basics $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $subtitle
     * @return Basics $this
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }
}
