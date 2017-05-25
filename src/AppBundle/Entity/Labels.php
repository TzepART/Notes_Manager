<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * Labels
 *
 * @ORM\Table(name="labels", indexes={@ORM\Index(name="fk_labels_sectors1_idx", columns={"sectors_id"}), @ORM\Index(name="fk_labels_layers1_idx", columns={"layers_id"})})
 * @ORM\Entity
 */
class Labels
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(name="radius", type="float")
     */
    private $radius;


    /**
     * @var float
     * @ORM\Column(name="angle", type="float")
     */
    private $angle;


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
     */
    private $notes;


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
     * Set sectors
     *
     * @param \AppBundle\Entity\Sectors $sectors
     * @return Labels
     */
    public function setSectors(Sectors $sectors = null)
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
    public function setLayers(Layers $layers = null)
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
