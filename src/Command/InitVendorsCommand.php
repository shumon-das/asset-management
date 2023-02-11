<?php

namespace App\Command;

use App\Entity\Assets;
use App\Entity\Vendors;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'add:vendors',
    description: 'create one or many vendors',
)]
class InitVendorsCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = $input->getArgument('arg1') ?? 1;

        for ($i = 0; $i < $this->getArg($input); ++$i) {
            $vendor = new Vendors();
            $vendor
                ->setVendorName($this->faker->realText(15))
                ->setEmail($this->faker->email())
                ->setPhone($this->faker->phoneNumber())
                ->setContactPerson($this->faker->realText(15))
                ->setDesignation($this->faker->realText(10))
                ->setCountry($this->faker->realText(30))
                ->setState($this->faker->realText(10))
                ->setCity($this->faker->realText(10))
                ->setZipCode(random_int(0, 6))
                ->setGstinNo(random_int(0, 10))
                ->setAddress($this->faker->realText(50))
                ->setDescription($this->faker->realText(150))
                ->setStatus(false)
                ->setIsDeleted(0)
                ->setStatus(true)
                ->setUpdatedAt(null)
                ->setUpdatedBy(null)
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy($this->getRandomUserId())
                ->setDeletedAt(null)
                ->setDeletedBy(null)
            ;
            $this->entityManager->persist($vendor);
            $this->entityManager->flush();
        }

        $io = new SymfonyStyle($input, $output);
        $io->success("Created $arg Vendors");

        return Command::SUCCESS;
    }
}
