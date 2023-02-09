<?php

namespace App\Command;

use App\Entity\Department;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $existenceUserId = $this->employeeRepository->findOneBy([], ['id' => 'DESC']);
        $department = new Department();
        $department
            ->setDepartmentName('Store')
            ->setContactPerson('Elon Musk')
            ->setContactPersonEmail('musk@gmail.com')
            ->setContactPersonPhone('+8812354456')
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreatedBy($existenceUserId->getId())
        ;
        $this->entityManager->persist($department);
        $this->entityManager->flush();

        $io->success('New Department created successfully');

        return Command::SUCCESS;
    }
}
