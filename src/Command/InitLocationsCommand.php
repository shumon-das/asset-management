<?php

namespace App\Command;

use App\Entity\Assets;
use App\Entity\Location;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'add:locations',
    description: 'create one or many locations',
)]
class InitLocationsCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = $input->getArgument('arg1') ?? 1;

        for ($i = 0; $i < $this->getArg($input); ++$i) {
            $location = new Location();
            $location
                ->setOfficeName($this->faker->realText(15))
                ->setCountry($this->faker->country())
                ->setState($this->faker->realText(10))
                ->setCity($this->faker->city())
                ->setZipCode(random_int(1, 6))
                ->setContactPersonName($this->faker->realText(15))
                ->setAddress1($this->faker->realText(50))
                ->setAddress2($this->faker->realText(50))
                ->setIsDeleted(0)
                ->setUpdatedAt(null)
                ->setUpdatedBy(null)
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy(1)
                ->setDeletedAt(null)
                ->setDeletedBy(null)
            ;
            $this->entityManager->persist($location);
        }
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success("Created $arg Locations");

        return Command::SUCCESS;
    }
}
