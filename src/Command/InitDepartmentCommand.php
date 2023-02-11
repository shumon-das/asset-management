<?php

namespace App\Command;

use App\Entity\Department;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'init:department',
    description: 'Add a short description for your command',
)]
class InitDepartmentCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        for ($i = 0; $i < $this->getArg($input); ++$i) {
            $department = new Department();
            $department
                ->setDepartmentName($this->faker->realText(10))
                ->setContactPerson($this->faker->name())
                ->setContactPersonEmail($this->faker->email())
                ->setContactPersonPhone($this->faker->phoneNumber())
                ->setIsDeleted(false)
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy($this->getRandomUserId());
            $this->entityManager->persist($department);
        }
        $this->entityManager->flush();

        $io->success("Created  Departments");

        return Command::SUCCESS;
    }
}
