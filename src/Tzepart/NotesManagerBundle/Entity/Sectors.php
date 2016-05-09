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


}
