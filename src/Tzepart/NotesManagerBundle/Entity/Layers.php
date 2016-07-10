<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Layers
 */
class Layers
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     */
    private $beginRadius;

    /**
     * @var float
     */
    private $endRadius;

    /**
     * @var \DateTime
     */
    private $dateCreate;

    /**
     * @var \DateTime
     */
    private $dateUpdate;

    /**
     * @var string
     */
    private $color;

    /**
     * @var \Tzepart\NotesManagerBundle\Entity\Circle
     */
    private $circle;

    /**
     * @ORM\OneToMany(targetEntity="Tzepart\NotesManagerBundle\Entity\Labels", mappedBy="layers")
     */
    private $labels;

    public function __construct() {
        $this->labels = new ArrayCollection();
    }

    public function getLabels()
    {
        return $this->labels;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set beginRadius
     *
     * @param float $beginRadius
     * @return Layers
     */
    public function setBeginRadius($beginRadius)
    {
        $this->beginRadius = $beginRadius;

        return $this;
    }

    /**
     * Get beginRadius
     *
     * @return float 
     */
    public function getBeginRadius()
    {
        return $this->beginRadius;
    }

    /**
     * Set endRadius
     *
     * @param float $endRadius
     * @return Layers
     */
    public function setEndRadius($endRadius)
    {
        $this->endRadius = $endRadius;

        return $this;
    }

    /**
     * Get endRadius
     *
     * @return float 
     */
    public function getEndRadius()
    {
        return $this->endRadius;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Layers
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Layers
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Layers
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set circle
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Circle $circle
     * @return Layers
     */
    public function setCircle(\Tzepart\NotesManagerBundle\Entity\Circle $circle = null)
    {
        $this->circle = $circle;

        return $this;
    }

    /**
     * Get circle
     *
     * @return \Tzepart\NotesManagerBundle\Entity\Circle 
     */
    public function getCircle()
    {
        return $this->circle;
    }
}
