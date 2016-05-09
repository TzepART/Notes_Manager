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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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


}
