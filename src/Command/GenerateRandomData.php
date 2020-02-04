<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Destination;
use App\Entity\Source;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * GenerateRandomData class for generating random data in source DB.
 */
class GenerateRandomData extends Command
{
    // The name of the command.
    protected static $defaultName = 'app:generate-random-data';
    
    // Container
    private $container = NULL;

    // Inject ContainerInterface to our command.
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        // Short description of the command.
        $this
            ->setDescription('Generates random data in Source database.')
            ->addArgument('count', InputArgument::REQUIRED, 'Number of entries to generate.')
            ->setHelp('This allows you generate random data to `user_details` table in Source database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get count argument.
        $count = $input->getArgument('count');

        // Retrieves a repository managed by the "source" entity manager.
        $em = $this->container->get('doctrine')->getManager('source');
        $dbCount = $em->getRepository(Source::class)->countAll();

        $output->writeln('There are ' . $dbCount . ' entries in the database.');
        $output->writeln('Generating ' . $count . ' random entries to Source database.');

        return 0;
    }
}
