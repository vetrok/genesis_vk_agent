<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photos
 *
 * @ORM\Table(name="photos", indexes={@ORM\Index(name="IDX_876E0D91137ABCF", columns={"album_id"})})
 * @ORM\Entity
 */
class Photos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="vk_id", type="integer", nullable=false)
     */
    private $vkId;

    /**
     * @var integer
     *
     * @ORM\Column(name="created", type="integer", nullable=false)
     */
    private $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Albums
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Albums")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     * })
     */
    private $album;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\PhotoSizes", mappedBy="photo", cascade={"persist"})
     */
    private $photoSizes;



    /**
     * Set vkId
     *
     * @param integer $vkId
     *
     * @return Photos
     */
    public function setVkId($vkId)
    {
        $this->vkId = $vkId;

        return $this;
    }

    /**
     * Get vkId
     *
     * @return integer
     */
    public function getVkId()
    {
        return $this->vkId;
    }

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return Photos
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
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
     * Set album
     *
     * @param \AppBundle\Entity\Albums $album
     *
     * @return Photos
     */
    public function setAlbum(\AppBundle\Entity\Albums $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \AppBundle\Entity\Albums
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @return mixed
     */
    public function getPhotoSizes()
    {
        return $this->photoSizes;
    }

    /**
     * @param mixed $photoSizes
     */
    public function setPhotoSizes($photoSizes)
    {
        $this->photoSizes = $photoSizes;
    }
}
