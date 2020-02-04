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
        // Retrieves a repository managed by the "customer" em
        $emSource = $this->container->get('doctrine')->getManager('source');
        $emDestination = $this->container->get('doctrine')->getManager('destination');
        // Get all records from the source DB table.
        $records = $emSource->getRepository(Source::class)->findAll();
        $recordsCount = count($records);
        $recordsSaved = 0;

        // Loop through all records.
        if ($recordsCount) {
            $batchSize = 30;
            foreach ($records as $i => $row) {
                $fullname = $row->getName() . ' ' . $row->getSurname();
                $product = $emDestination->getRepository(Destination::class)->findOneBy([
                    'fullname' => $fullname,
                    'e_mail' => $row->getEmail(),
                ]);
                // Create record if none was found.
                if (empty($product)) {
                    $destination = new Destination();
                    $destination->setFullname($fullname);
                    $destination->setEMail($row->getEmail());
                    // Parse float as decimal.
                    $destination->setBalance(sprintf('%.2f', $row->getData()));
                    $destination->setTotalpurchase($row->getData2());
                    // Prepare object for saving.
                    $emDestination->persist($destination);
                    if (($i % $batchSize) === 0) {
                        // Execute transaction every 30 entries.
                        $emDestination->flush();
                        $emDestination->clear();
                    }
                    $recordsSaved++;
                }
            }
            // Persist objects that did not make up an entire batch.
            $emDestination->flush();
            $emDestination->clear();
            unset($destination);
        }

        $output->writeln($recordsSaved . '/' . $recordsCount . ' records moved to Destination database.');

        return 1;
    }
}
