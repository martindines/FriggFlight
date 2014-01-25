<?php

namespace Frigg\FlightBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AirportService extends FlightParentAbstract
{
    protected $params = array();

    /**
     * Airport subclass constructor
     * @var EntityManager $em
     * @var string $config
     **/
    public function __construct(EntityManager $em, SessionInterface $session, $config)
    {
        parent::__construct($em, $session, $config);
    }

    /**
     * Get all airport entities
     * @return array
     **/
    public function getAll()
    {
        return $this->em->getRepository('FriggFlightBundle:Airport')->findAll();
    }

   /**
     * Get session entity from session
     * @return object
     **/
    public function getSessionEntity()
    {
        $entity = $this->em->getRepository('FriggFlightBundle:Airport')->find($this->getSession());

        if (!$entity) {
           throw new NotFoundHttpException('Unable to find airport entity');
        }

        $this->setParent($entity);
        return $entity;
    }

    /**
     * Set airport entity
     * @var integer $parentId
     * @return AirportService
     **/
    public function setParentById($parentId)
    {
        $airportEntity = $this->em->getRepository('FriggFlightBundle:Airport')->find($parentId);

        if (!$airportEntity) {
            throw new NotFoundHttpException('Unable to find airport entity');
        }

        $this->setParent($airportEntity);
        return $this;
    }

    /**
     * Set flight entity in context of parent
     * @var integer $parentId
     * @var integer $flightId
     * @return AirportService
     **/
    public function setFlightById($parentId, $flightId)
    {
        $flightEntity = $this->em->getRepository('FriggFlightBundle:Flight')->findOneBy(
            array(
                'id' => $flightId,
                'airport' => $parentId,
            )
        );

        if (!$flightEntity) {
            throw new NotFoundHttpException('Unable to find flight entity');
        }

        $this->setFlight($flightEntity);
        return $this;
    }

    /**
     * Get all Avinor airports
     * @return array
     **/
    public function getAvinorAirports()
    {
        return $this->em->createQueryBuilder()->select('a')
            ->from('FriggFlightBundle:Airport', 'a')
            ->where('a.is_avinor = :is_avinor')
            ->setParameter('is_avinor', true)
            ->getQuery()
            ->getResult();
    }


    /**
     * Fetch scheduled flights group for this airport
     * @return array
     **/
    protected function loadFlightGroup()
    {
        if (!$this->parentEntity) {
            throw new NotFoundHttpException('Unable to load flights. Missing parent entity.');
        }

        $direction = $this->getParam('direction');
        $isDelayed = ($this->getParam('is_delayed') == 'Y');
        $fromTime = ($this->getParam('from_time')) ? $this->getParam('from_time') : mktime(0, 0, 0);
        $toTime = ($this->getParam('to_time')) ? $this->getParam('to_time') : mktime(23, 59, 59);

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('f')
            ->from('FriggFlightBundle:Flight', 'f')
            ->where($qb->expr()->andX(
                $qb->expr()->lte('f.schedule_time', ':to_time'),
                $qb->expr()->orX(
                    $qb->expr()->gte('f.schedule_time', ':from_time'),
                    $qb->expr()->andX(
                        $qb->expr()->isNotNull('f.flight_status_time'),
                        $qb->expr()->gte('f.flight_status_time', ':status_from_time'),
                        $qb->expr()->eq('f.flight_status', ':status_newtime_id')
                    )
                )
            ))
            ->andWhere('f.airport = :airport');

        if ($direction) {
            $query->andWhere('f.arr_dep = :direction');
            $query->setParameter('direction', $direction);
        }

        if ($isDelayed) {
            $query->andWhere('f.is_delayed = :is_delayed');
            $query->setParameter('is_delayed', $isDelayed);
        }

        $query->orderBy('f.schedule_time', 'ASC')
            ->setParameter('airport', $this->parentEntity->getId())
            ->setParameter('from_time', new \DateTime(date('Y-m-d H:i:s', $fromTime)), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('to_time', new \DateTime(date('Y-m-d H:i:s', $toTime)), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('status_from_time', new \DateTime(date('Y-m-d H:i:s', ($fromTime - (24 * (60 * 60))))), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('status_newtime_id', 2);

        return $query->getQuery()->getResult();
    }

    public function getGraphData()
    {
        return array();
    }
}
