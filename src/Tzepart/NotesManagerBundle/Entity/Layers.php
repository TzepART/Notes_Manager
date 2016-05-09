<?php

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Layers
 *
 * @ORM\Table(name="layers", indexes={@ORM\Index(name="fk_layers_circle1_idx", columns={"circle_id"}), @ORM\Index(name="fk_layers_colors1_idx", columns={"colors_id"})})
 * @ORM\Entity
 */
class Layers
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
     * @ORM\Column(name="begin_radius", type="float", precision=10, scale=0, nullable=true)
     */
    private $beginRadius;

    /**
     * @var float
     *
     * @ORM\Column(name="end_radius", type="float", precision=10, scale=0, nullable=true)
     */
    private $endRadius;

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
