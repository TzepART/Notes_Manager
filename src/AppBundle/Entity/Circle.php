<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Circle
 *
 * @ORM\Table(name="circle", indexes={@ORM\Index(name="fk_circle_users1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Circle
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256, nullable=false)
     */
    private $name;

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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sectors", mappedBy="circle")
     */
    private $sectors;    
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Layers", mappedBy="circle")
     */
    private $layers;

    public function __construct() {
        $this->layers = new ArrayCollection();
        $this->sectors = new ArrayCollection();
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


    public function getSectors()
    {
        return $this->sectors;
    }    
    
    
    public function getLayers()
    {
        return $this->layers;
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
     * Set name
     *
     * @param string $name
     * @return Circle
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Circle
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
     * @return Circle
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Circle
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


}
