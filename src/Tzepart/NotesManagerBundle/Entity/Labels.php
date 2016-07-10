<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Labels
 *
 * @ORM\Table(name="labels", indexes={@ORM\Index(name="fk_labels_sectors1_idx", columns={"sectors_id"}), @ORM\Index(name="fk_labels_layers1_idx", columns={"layers_id"})})
 * @ORM\Entity
 */
class Labels
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="radius", type="float")
     */
    private $radius;


    /**
     * @var float
     *
     * @ORM\Column(name="angle", type="float")
     */
    private $angle;

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
     * @var \Tzepart\NotesManagerBundle\Entity\Sectors
     *
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Sectors")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sectors_id", referencedColumnName="id")
     * })
     */
    private $sectors;

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
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    /**
     * @return float
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * @param float $angle
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Labels
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
     * @return Labels
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
     * Set sectors
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Sectors $sectors
     * @return Labels
     */
    public function setSectors(\Tzepart\NotesManagerBundle\Entity\Sectors $sectors = null)
    {
        $this->sectors = $sectors;

        return $this;
    }

    /**
     * Get sectors
     *
     * @return \Tzepart\NotesManagerBundle\Entity\Sectors
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * Set layers
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Layers $layers
     * @return Labels
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
