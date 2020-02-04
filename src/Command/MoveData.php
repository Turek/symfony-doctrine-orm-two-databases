<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Destination;
use App\Entity\Source;
use Psr\Container\ContainerInterface;

/**
 * MoveData class for moving data from source DB to destination DB.
 */
class MoveData extends Command
{
    // The name of the command.
    protected static $defaultName = 'app:move-data';
    
    // Container
    private $container = NULL;

    // Inject ContainerInterface to our command.
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        // $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        // Short description of the command.
        $this
            ->setDescription('Moves data from Source database to Destination database.')
            ->setHelp('This allows you move data from `user_details` table in Source database across to `customer_details` in Destination database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Command execution.
        $output->writeln('Test!');

        // Retrieves a repository managed by the "customer" em
        $em = $this->container->get('doctrine')->getManager('source');
        $records = $em->getRepository(Source::class)->findAll();

        print_r($records);

        return 0;
    }
}
