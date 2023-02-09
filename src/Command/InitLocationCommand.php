<?php

namespace App\Command;

use App\Entity\Location;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'init:location',
    description: 'Add a short description for your command',
)]
class InitLocationCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $location = new Location();
        $location
            ->setOfficeName('Dhaka Office')
            ->setCountry('Bangladesh')
            ->setState('Dhaka')
            ->setCity('Dhaka')
            ->setZipCode(1234)
            ->setContactPersonName('Elon Musk')
            ->setAddress1('abcd')
            ->setAddress2('efgh')
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreatedBy(1)
        ;
        $this->entityManager->persist($location);
        $this->entityManager->flush();

        $io->success('New Location Created Successfully');

        return Command::SUCCESS;
    }
}
