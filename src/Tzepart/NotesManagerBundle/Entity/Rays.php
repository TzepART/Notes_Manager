<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rays
 *
 * @ORM\Table(name="rays", indexes={@ORM\Index(name="fk_rays_sectors1_idx", columns={"sectors_id"})})
 * @ORM\Entity
 */
class Rays
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
     * @ORM\Column(name="angle", type="float", precision=10, scale=0, nullable=true)
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set angle
     *
     * @param float $angle
     * @return Rays
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;

        return $this;
    }

    /**
     * Get angle
     *
     * @return float 
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Rays
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
     * @return Rays
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
     * @return Rays
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
}
