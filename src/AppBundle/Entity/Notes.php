<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;



/**
 * Notes
 *
 * @ORM\Table(name="notes", indexes={@ORM\Index(name="fk_notes_users_idx", columns={"users_id"}), @ORM\Index(name="fk_notes_labels1_idx", columns={"labels_id"})})
 * @ORM\Entity
 */
class Notes
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=1024, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Labels
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Labels")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="labels_id", referencedColumnName="id")
     * })
     */
    private $labels;

    public function __construct() {
        $this->labels = new ArrayCollection();
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
     * @return Notes
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
     * Set text
     *
     * @param string $text
     * @return Notes
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Notes
     */
    public function setUser(User $user = null)
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

    /**
     * Set labels
     *
     * @param \AppBundle\Entity\Labels $labels
     * @return Notes
     */
    public function setLabels(Labels $labels = null)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Get labels
     *
     * @return \AppBundle\Entity\Labels 
     */
    public function getLabels()
    {
        return $this->labels;
    }

}
