<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var \Tzepart\NotesManagerBundle\Entity\Circle
     * 
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Circle", inversedBy="sectors")
     * @ORM\JoinColumn(name="circle_id", referencedColumnName="id")
     *
     */
    private $circle;


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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Sectors
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
     * @return Sectors
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
     * @param \Tzepart\NotesManagerBundle\Entity\Circle $circle
     * @return Sectors
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
