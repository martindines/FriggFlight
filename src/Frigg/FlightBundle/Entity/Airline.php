<?php

namespace Frigg\FlightBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Frigg\FlightBundle\Entity\Airline
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Frigg\FlightBundle\Entity\AirlineRepository")
 *
 * @ExclusionPolicy("all")
 */
class Airline
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=20, nullable=false)
     *
     * @Expose
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Flight", mappedBy="airline")
     *
     */
    private $flights;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->flights = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set code
     *
     * @param string $code
     * @return Airline
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Airline
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
     * Add flights
     *
     * @param \Frigg\FlightBundle\Entity\Flight $flights
     * @return Airline
     */
    public function addFlight(\Frigg\FlightBundle\Entity\Flight $flights)
    {
        $this->flights[] = $flights;

        return $this;
    }

    /**
     * Remove flights
     *
     * @param \Frigg\FlightBundle\Entity\Flight $flights
     */
    public function removeFlight(\Frigg\FlightBundle\Entity\Flight $flights)
    {
        $this->flights->removeElement($flights);
    }

    /**
     * Get flights
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlights()
    {
        return $this->flights;
    }
}
