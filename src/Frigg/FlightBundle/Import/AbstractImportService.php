<?php

namespace Frigg\FlightBundle\Import;

use Frigg\FlightBundle\Entity\LastUpdated;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractImportService
{
    protected $em = null;
    protected $container = null;

    protected $response = array();
    protected $data = array();

    /**
     * Parent constructor
     * @var ContainerInterface $container
     **/
    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->container = $container;
    }

    /**
     * Print import status
     * @return string
     **/
    abstract public function output();

    /**
     * Execute importer
     * @return AbstractImportService
     **/
    abstract public function run();

    /**
     * Get response from endpoint
     * @var string $target Location of endpoint
     * @return bool|array
     **/
    protected function request($target)
    {
        printf('Request %s%s', $target, PHP_EOL);
        if (($content = file_get_contents($target, 'r')) !== false) {
            try {
                $this->response = simplexml_load_string($content);
                return $this->response;
            } catch(\Exception $e) {
                printf('Error: %s%s', $e->getMessage(), PHP_EOL);
            }
        }

        return false;
    }

    /**
     * Get last updated timestamp
     * @return string
     **/
    final protected function getLastUpdated()
    {
        if ($lastUpdated = $this->em->getRepository('FriggFlightBundle:LastUpdated')->findOneByClass(get_called_class())) {
            return $lastUpdated->getTimestamp();
        }

        return 0;
    }

   /**
     * Update last updated timestamp to now
     * @return AbstractImportService
     **/
    final protected function setLastUpdated()
    {
        $importerClass = get_called_class();
        if (!$lastUpdated = $this->em->getRepository('FriggFlightBundle:LastUpdated')->findOneByClass($importerClass)) {
            $lastUpdated = new LastUpdated;
        }

        $lastUpdated->setClass($importerClass);
        $lastUpdated->setTimestamp(time());

        $this->em->persist($lastUpdated);
        $this->em->flush();
        return $this;
    }
}
