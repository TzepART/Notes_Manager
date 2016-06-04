<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Labels
 *
 * @ORM\Table(name="labels", indexes={@ORM\Index(name="fk_labels_rays1_idx", columns={"rays_id"}), @ORM\Index(name="fk_labels_radius1_idx", columns={"radius_id"})})
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
     * @var \Tzepart\NotesManagerBundle\Entity\Rays
     *
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Rays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rays_id", referencedColumnName="id")
     * })
     */
    private $rays;

    /**
     * @var \Tzepart\NotesManagerBundle\Entity\Radius
     *
     * @ORM\ManyToOne(targetEntity="Tzepart\NotesManagerBundle\Entity\Radius")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="radius_id", referencedColumnName="id")
     * })
     */
    private $radius;



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
     * Set rays
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Rays $rays
     * @return Labels
     */
    public function setRays(\Tzepart\NotesManagerBundle\Entity\Rays $rays = null)
    {
        $this->rays = $rays;

        return $this;
    }

    /**
     * Get rays
     *
     * @return \Tzepart\NotesManagerBundle\Entity\Rays 
     */
    public function getRays()
    {
        return $this->rays;
    }

    /**
     * Set radius
     *
     * @param \Tzepart\NotesManagerBundle\Entity\Radius $radius
     * @return Labels
     */
    public function setRadius(\Tzepart\NotesManagerBundle\Entity\Radius $radius = null)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get radius
     *
     * @return \Tzepart\NotesManagerBundle\Entity\Radius 
     */
    public function getRadius()
    {
        return $this->radius;
    }
}
