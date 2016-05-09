<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Layers
 *
 * @ORM\Table(name="layers", indexes={@ORM\Index(name="fk_layers_circle1_idx", columns={"circle_id"}), @ORM\Index(name="fk_layers_colors1_idx", columns={"colors_id"})})
 * @ORM\Entity
 */
class Layers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="begin_radius", type="float", precision=10, scale=0, nullable=true)
     */
    private $beginRadius;

    /**
     * @var float
     *
     * @ORM\Column(name="end_radius", type="float", precision=10, scale=0, nullable=true)
     */
    private $endRadius;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var \Tzepart\NotesManagerBundle\Entity\Circle
     *
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Circle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="circle_id", referencedColumnName="id")
     * })
     */
    private $circle;

    /**
     * @var \Tzepart\NotesManagerBundle\Entity\Colors
     *
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Colors")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="colors_id", referencedColumnName="id")
     * })
     */
    private $colors;



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

    /**
     * Set colors
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Colors $colors
     * @return Layers
     */
    public function setColors(\Tzepart\NotesManagerBundle\Entity\Colors $colors = null)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get colors
     *
     * @return \Tzepart\NotesManagerBundle\Entity\Colors 
     */
    public function getColors()
    {
        return $this->colors;
    }
}
