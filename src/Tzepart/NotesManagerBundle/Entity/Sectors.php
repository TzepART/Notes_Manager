<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sectors
 *
 * @ORM\Table(name="sectors", indexes={@ORM\Index(name="fk_sectors_circle1_idx", columns={"circle_id"}), @ORM\Index(name="fk_sectors_colors1_idx", columns={"colors_id"})})
 * @ORM\Entity
 */
class Sectors
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
     * @ORM\Column(name="begin_angle", type="float", precision=10, scale=0, nullable=true)
     */
    private $beginAngle;

    /**
     * @var float
     *
     * @ORM\Column(name="end_angle", type="float", precision=10, scale=0, nullable=true)
     */
    private $endAngle;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_sector_id", type="integer", nullable=true)
     */
    private $parentSectorId;

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

    /**
     * Set colors
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Colors $colors
     * @return Sectors
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
