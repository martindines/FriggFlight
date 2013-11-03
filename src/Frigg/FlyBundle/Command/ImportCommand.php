<?php

namespace Frigg\FlyBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('friggfly:import')
            ->setDescription('Import flights')
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'What to import (e.g. flights)?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timer = microtime(true);
        $container = $this->getContainer();

        $type = $input->getArgument('type');
        switch($type) {
            case 'flights':
                $output->writeln('Running flight importer');
                $importer = $container->get('frigg_fly.flight_import');
                $importer->run();
                break;

            default:
                $output->writeln('Unknown type');
                break;
        }

        $output->writeln(sprintf('Took %.02f ms', ((microtime(true) - $timer) * 1000)));
    }
}
