<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Appointment.
 *
 * @ORM\Table(name="appointment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AppointmentRepository")
 *
 * @ApiResource
 */
class Appointment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Resource", mappedBy="appointment")
     */
    private $resources;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Appointment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Appointment
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add resource.
     *
     * @param resource $resource
     *
     * @return Appointment
     */
    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
        $resource->setAppointment($this);

        return $this;
    }

    /**
     * Remove resource.
     *
     * @param resource $resource
     */
    public function removeResource(Resource $resource)
    {
        $resource->setAppointment(null);
        $this->resources->removeElement($resource);
    }

    /**
     * Get resources.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResources()
    {
        return $this->resources;
    }
}
