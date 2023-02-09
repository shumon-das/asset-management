<?php

namespace App\Command;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractCommand extends Command
{
    public UserPasswordHasherInterface $hasher;
    protected EntityManagerInterface $entityManager;
    protected EmployeeRepository $employeeRepository;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $entityManager,
        EmployeeRepository $employeeRepository,
    ){
        parent::__construct();
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
    }
        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            return Command::SUCCESS;
        }
}