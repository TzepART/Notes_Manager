<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Radius
 *
 * @ORM\Table(name="radius", indexes={@ORM\Index(name="fk_radius_layers1_idx", columns={"layers_id"})})
 * @ORM\Entity
 */
class Radius
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
     * @ORM\Column(name="radius", type="float", precision=10, scale=0, nullable=true)
     */
    private $radius;

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
     * @var \Tzepart\NotesManagerBundle\Entity\Layers
     *
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Layers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="layers_id", referencedColumnName="id")
     * })
     */
    private $layers;



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
     * Set radius
     *
     * @param float $radius
     * @return Radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get radius
     *
     * @return float 
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Radius
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
     * @return Radius
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
     * Set layers
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Layers $layers
     * @return Radius
     */
    public function setLayers(\Tzepart\NotesManagerBundle\Entity\Layers $layers = null)
    {
        $this->layers = $layers;

        return $this;
    }

    /**
     * Get layers
     *
     * @return \Tzepart\NotesManagerBundle\Entity\Layers 
     */
    public function getLayers()
    {
        return $this->layers;
    }
}
