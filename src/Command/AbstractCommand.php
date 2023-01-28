<?php

namespace App\Command;

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

    public function __construct(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $entityManager
    ){
        parent::__construct();
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
    }
        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            return Command::SUCCESS;
        }
}