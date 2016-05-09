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


}
