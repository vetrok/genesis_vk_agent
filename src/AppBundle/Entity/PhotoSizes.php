<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhotoSizes
 *
 * @ORM\Table(name="photo_sizes", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_CCF80DE7E9E4C8CF7C0246A", columns={"photo_id", "size"})}, indexes={@ORM\Index(name="IDX_CCF80DE7E9E4C8C", columns={"photo_id"})})
 * @ORM\Entity
 */
class PhotoSizes
{
    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=32, nullable=false)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=false)
     */
    private $link;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Photos
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Photos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     * })
     */
    private $photo;



    /**
     * Set size
     *
     * @param string $size
     *
     * @return PhotoSizes
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return PhotoSizes
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
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
     * Set photo
     *
     * @param \AppBundle\Entity\Photos $photo
     *
     * @return PhotoSizes
     */
    public function setPhoto(\AppBundle\Entity\Photos $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AppBundle\Entity\Photos
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
