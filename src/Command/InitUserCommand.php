<?php

namespace App\Command;

use App\Entity\Employee;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'init:user',
    description: 'Add a short description for your command',
)]
class InitUserCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $employee = new Employee();
        $employee
            ->setUuid(Uuid::v1())
            ->setName('shumon')
            ->setEmail('shumonsb@gmail.com')
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setPassword($this->hasher->hashPassword($employee, '123456'))
            ->setDepartment(1)
            ->setLocation(1)
            ->setReportingManager(1)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreatedBy(1)
            ->setIsDeleted(0)
        ;
        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        $io->success('--- Init user created ---');

        return Command::SUCCESS;
    }
}


