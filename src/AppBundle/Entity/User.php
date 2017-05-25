<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;



/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logged", type="datetime", nullable=false)
     */
    protected $logged;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notes", mappedBy="user")
     */
    protected $notes;
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Circle", mappedBy="user")
     */
    protected $circles;

    public function __construct()
    {
        parent::__construct();
        $this->salt = null;
        $this->notes = new ArrayCollection();
        $this->circles = new ArrayCollection();
        $this->isActive = true;
    }


    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return mixed
     */
    public function getCircles()
    {
        return $this->circles;
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
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }


    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }


    /**
     * Set logged
     *
     * @param \DateTime $logged
     * @return User
     */
    public function setLogged($logged)
    {
        $this->logged = $logged;

        return $this;
    }

    /**
     * Get logged
     *
     * @return \DateTime 
     */
    public function getLogged()
    {
        return $this->logged;
    }

}
