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
        $count = (int) $input->getArgument('count');

        // Retrieves a repository managed by the "source" entity manager.
        $em = $this->container->get('doctrine')->getManager('source');
        $dbCount = $em->getRepository(Source::class)->countAll();

        $output->writeln('There are ' . $dbCount . ' entries in the database.');
        $output->write('Generating ' . $count . ' random entries to Source database... ');

        if ($count > 0) {
            for($i = 0; $i == $count; $i++) {
                $row = $this->generateFakeRow();
                $source = new Source();
                $source->setName($row['name']);
                $source->setSurname($row['surname']);
                $source->setEmail($row['email']);
                $source->setData($row['data']);
                $source->setData2($row['data2']);
                // Prepare object for saving.
                $em->persist($source);
                // Execute the save query.
                $em->flush();
            }
            unset($source);
        }
        $output->writeln('Done.');

        return 1;
    }

    private function generateFakeRow() {
        $faker = \Faker\Factory::create();
        return [
            'name' => $faker->firstName(),
            'surname' => $faker->lastName,
            'email' => $faker->email,
            'data' => $faker->randomFloat(),
            'data2' => $faker->randomFloat(2,200,1500),
        ];
    }
}
