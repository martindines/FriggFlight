<?php

namespace Frigg\FlightBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
/**
 * Frigg\FlightBundle\Entity\Airport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Frigg\FlightBundle\Entity\FlightRepository")
 *
 * @ExclusionPolicy("all")
 */
class Flight
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
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Expose
     */
    private $remote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $dom_int;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $schedule_time;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $arr_dep;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $check_in;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $gate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Expose
     */
    private $is_delayed;

    /**
     * @ORM\ManyToOne(targetEntity="Airline", inversedBy="flights", cascade={"persist"})
     * @ORM\JoinColumn(name="airline_id", referencedColumnName="id", nullable=true)
     *
     * @Expose
     */
    private $airline;

    /**
     * @ORM\ManyToOne(targetEntity="Airport", inversedBy="flights", cascade={"persist"})
     * @ORM\JoinColumn(name="airport_id", referencedColumnName="id", nullable=true)
     *
     * @Expose
     */
    private $airport;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $flight_status_time;

    /**
     * @ORM\ManyToOne(targetEntity="FlightStatus", inversedBy="flights", cascade={"persist"})
     * @ORM\JoinColumn(name="flight_status_id", referencedColumnName="id", nullable=true)
     *
     * @Expose
     */
    private $flight_status;

    /**
     * @ORM\ManyToMany(targetEntity="Airport", inversedBy="via_flights")
     * @ORM\JoinTable(
     *     name="FlightViaAiport",
     *     joinColumns={@ORM\JoinColumn(name="flight_id", referencedColumnName="id", nullable=true)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="airport_id", referencedColumnName="id", nullable=true)}
     * )
     *
     * @Expose
     */
    private $via_airports;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->via_airports = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set remote
     *
     * @param string $remote
     * @return Flight
     */
    public function setRemote($remote)
    {
        $this->remote = $remote;

        return $this;
    }

    /**
     * Get remote
     *
     * @return string
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return Flight
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set dom_int
     *
     * @param string $domInt
     * @return Flight
     */
    public function setDomInt($domInt)
    {
        $this->dom_int = $domInt;

        return $this;
    }

    /**
     * Get dom_int
     *
     * @return string
     */
    public function getDomInt()
    {
        return $this->dom_int;
    }

    /**
     * Set schedule_time
     *
     * @param string $scheduleTime
     * @return Flight
     */
    public function setScheduleTime($scheduleTime)
    {
        $this->schedule_time = $scheduleTime;

        return $this;
    }

    /**
     * Get schedule_time
     *
     * @return string
     */
    public function getScheduleTime()
    {
        return $this->schedule_time;
    }

    /**
     * Set arr_dep
     *
     * @param string $arrDep
     * @return Flight
     */
    public function setArrDep($arrDep)
    {
        $this->arr_dep = $arrDep;

        return $this;
    }

    /**
     * Get arr_dep
     *
     * @return string
     */
    public function getArrDep()
    {
        return $this->arr_dep;
    }

    /**
     * Set check_in
     *
     * @param string $checkIn
     * @return Flight
     */
    public function setCheckIn($checkIn)
    {
        $this->check_in = $checkIn;

        return $this;
    }

    /**
     * Get check_in
     *
     * @return string
     */
    public function getCheckIn()
    {
        return $this->check_in;
    }

    /**
     * Set gate
     *
     * @param string $gate
     * @return Flight
     */
    public function setGate($gate)
    {
        $this->gate = $gate;

        return $this;
    }

    /**
     * Get gate
     *
     * @return string
     */
    public function getGate()
    {
        return $this->gate;
    }

    /**
     * Set is_delayed
     *
     * @param boolean $is_delayed
     * @return Flight
     */
    public function setIsDelayed($is_delayed)
    {
        $this->is_delayed = $is_delayed;

        return $this;
    }

    /**
     * Get delayed
     *
     * @return boolean
     */
    public function getIsDelayed()
    {
        return $this->is_delayed;
    }

    /**
     * Set flight_status_time
     *
     * @param string $flightStatusTime
     * @return Flight
     */
    public function setFlightStatusTime($flightStatusTime)
    {
        $this->flight_status_time = $flightStatusTime;

        return $this;
    }

    /**
     * Get flight_status_time
     *
     * @return string
     */
    public function getFlightStatusTime()
    {
        return $this->flight_status_time;
    }

    /**
     * Set airline
     *
     * @param \Frigg\FlightBundle\Entity\Airline $airline
     * @return Flight
     */
    public function setAirline(\Frigg\FlightBundle\Entity\Airline $airline = null)
    {
        $this->airline = $airline;

        return $this;
    }

    /**
     * Get airline
     *
     * @return \Frigg\FlightBundle\Entity\Airline
     */
    public function getAirline()
    {
        return $this->airline;
    }

    /**
     * Set airport
     *
     * @param \Frigg\FlightBundle\Entity\Airport $airport
     * @return Flight
     */
    public function setAirport(\Frigg\FlightBundle\Entity\Airport $airport = null)
    {
        $this->airport = $airport;

        return $this;
    }

    /**
     * Get airport
     *
     * @return \Frigg\FlightBundle\Entity\Airport
     */
    public function getAirport()
    {
        return $this->airport;
    }

    /**
     * Set flight_status
     *
     * @param \Frigg\FlightBundle\Entity\FlightStatus $flightStatus
     * @return Flight
     */
    public function setFlightStatus(\Frigg\FlightBundle\Entity\FlightStatus $flightStatus = null)
    {
        $this->flight_status = $flightStatus;

        return $this;
    }

    /**
     * Get flight_status
     *
     * @return \Frigg\FlightBundle\Entity\FlightStatus
     */
    public function getFlightStatus()
    {
        return $this->flight_status;
    }

    /**
     * Add via_airports
     *
     * @param \Frigg\FlightBundle\Entity\Airport $viaAirports
     * @return Flight
     */
    public function addViaAirport(\Frigg\FlightBundle\Entity\Airport $viaAirports)
    {
        $this->via_airports[] = $viaAirports;

        return $this;
    }

    /**
     * Remove via_airports
     *
     * @param \Frigg\FlightBundle\Entity\Airport $viaAirports
     */
    public function removeViaAirport(\Frigg\FlightBundle\Entity\Airport $viaAirports)
    {
        $this->via_airports->removeElement($viaAirports);
    }

    /**
     * Get via_airports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getViaAirports()
    {
        return $this->via_airports;
    }
}