<?php

namespace AppBundle\Entity;

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
     * @var \AppBundle\Entity\Sectors
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sectors")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sectors_id", referencedColumnName="id")
     * })
     */
    private $sectors;

    /**
     * @var \AppBundle\Entity\Layers
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Layers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="layers_id", referencedColumnName="id")
     * })
     */
    private $layers;

    /**
     * @var \AppBundle\Entity\Notes
     *
     */
    private $notes;


    public function __construct() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @return Notes
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Notes $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
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
     * @param \AppBundle\Entity\Sectors $sectors
     * @return Labels
     */
    public function setSectors(\AppBundle\Entity\Sectors $sectors = null)
    {
        $this->sectors = $sectors;

        return $this;
    }

    /**
     * Get sectors
     *
     * @return \AppBundle\Entity\Sectors
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * Set layers
     *
     * @param \AppBundle\Entity\Layers $layers
     * @return Labels
     */
    public function setLayers(\AppBundle\Entity\Layers $layers = null)
    {
        $this->layers = $layers;

        return $this;
    }

    /**
     * Get layers
     *
     * @return \AppBundle\Entity\Layers
     */
    public function getLayers()
    {
        return $this->layers;
    }
}
