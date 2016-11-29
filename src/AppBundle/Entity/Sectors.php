<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Sectors
 */
class Sectors
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
    private $beginAngle;

    /**
     * @var float
     */
    private $endAngle;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $parentSectorId;

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
     * @var \AppBundle\Entity\Circle
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Circle", inversedBy="sectors")
     * @ORM\JoinColumn(name="circle_id", referencedColumnName="id")
     *
     */
    private $circle;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Labels", mappedBy="sectors")
     */
    private $labels;

    public function __construct() {
        $this->labels = new ArrayCollection();
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
     * Set beginAngle
     *
     * @param float $beginAngle
     * @return Sectors
     */
    public function setBeginAngle($beginAngle)
    {
        $this->beginAngle = $beginAngle;

        return $this;
    }

    /**
     * Get beginAngle
     *
     * @return float 
     */
    public function getBeginAngle()
    {
        return $this->beginAngle;
    }

    /**
     * Set endAngle
     *
     * @param float $endAngle
     * @return Sectors
     */
    public function setEndAngle($endAngle)
    {
        $this->endAngle = $endAngle;

        return $this;
    }

    /**
     * Get endAngle
     *
     * @return float 
     */
    public function getEndAngle()
    {
        return $this->endAngle;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Sectors
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set parentSectorId
     *
     * @param integer $parentSectorId
     * @return Sectors
     */
    public function setParentSectorId($parentSectorId)
    {
        $this->parentSectorId = $parentSectorId;

        return $this;
    }

    /**
     * Get parentSectorId
     *
     * @return integer 
     */
    public function getParentSectorId()
    {
        return $this->parentSectorId;
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
     * @return Sectors
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
     * @param \AppBundle\Entity\Circle $circle
     * @return Sectors
     */
    public function setCircle(\AppBundle\Entity\Circle $circle = null)
    {
        $this->circle = $circle;

        return $this;
    }

    /**
     * Get circle
     *
     * @return \AppBundle\Entity\Circle 
     */
    public function getCircle()
    {
        return $this->circle;
    }
}
