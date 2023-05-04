<?php

namespace App\Command;

use App\Entity\Route;
use App\Entity\Stop;
use App\Entity\StopTime;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function Symfony\Component\String\u;

#[AsCommand(
    name: 'app:import-gtfs',
    description: 'Import données TAN',
)]
class ImportGtfsCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        // SF5 et avant => $this->em->getConnection()->getConfiguration()->setSQLLogger(null); // <=== Astuce
        // SF6 --no-debug
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import données TAN')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de l\'entité à importer.')
            ->setHelp('php bin/console app:import-gtfs Route && php bin/console app:import-gtfs Stop && php bin/console app:import-gtfs Trip && php bin/console app:import-gtfs StopTime --no-debug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = dirname(__DIR__, 2).'/datas/'.u($input->getArgument('name'))->snake().'s.txt';
        $entityName = 'App\Entity\\'.$input->getArgument('name');
        $io->note('Import du fichier : '.$fileName);
        if (StopTime::class !== $entityName) {
            $fp = file($fileName);
            $progressBar = new ProgressBar($output, \count($fp));
        } else {
            $progressBar = new ProgressBar($output);
        }
        $progressBar->setFormat('debug');
        $progressBar->start();
        $i = 0;
        $keys = [];
        if (false !== ($handle = fopen($fileName, 'r'))) {
            while (false !== ($data = fgetcsv($handle, 1000, ','))) {
                ++$i;
                if (1 === $i) {
                    $keys = $data;

                    continue;
                }
                $data = array_combine($keys, $data);
                $data = $this->loadEntityLink($entityName, $data);
                $entity = $entityName::createFromCsv($data);
                $this->em->persist($entity);
                if (0 === $i % 1000) {
                    $this->em->flush();
                    $this->em->clear();
                }
                $progressBar->advance();
            }
            $this->em->flush();
            $this->em->clear();
            fclose($handle);
        }
        $progressBar->finish();
        $io->newLine(2);
        $io->success('Fin de l\'import');

        return Command::SUCCESS;
    }

    private function loadEntityLink(string $entityName, array $datas): array
    {
        if (Stop::class === $entityName) {
            $datas['parent'] = null;
            if (null !== $datas['parent_station']) {
                $datas['parent'] = $this->em->find(Stop::class, $datas['parent_station']);
            }
        }

        if (Trip::class === $entityName) {
            $datas['route'] = $this->em->find(Route::class, $datas['route_id']);
        }
        if (StopTime::class == $entityName) {
            $datas['trip'] = $this->em->find(Trip::class, $datas['trip_id']);

            $datas['stop'] = $this->em->find(Stop::class, $datas['stop_id']);
        }

        return $datas;
    }
}
