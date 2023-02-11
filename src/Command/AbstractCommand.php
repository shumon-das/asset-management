<?php

namespace App\Command;

use App\Repository\EmployeeRepository;
use App\Repository\LocationRepository;
use App\Repository\ProductsRepository;
use App\Repository\VendorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractCommand extends Command
{
    protected Generator $faker;
    public UserPasswordHasherInterface $hasher;
    protected EntityManagerInterface $entityManager;
    protected EmployeeRepository $employeeRepository;
    protected VendorsRepository $vendorsRepository;
    protected ProductsRepository $productsRepository;
    protected LocationRepository $locationRepository;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $entityManager,
        EmployeeRepository $employeeRepository,
        VendorsRepository $vendorsRepository,
        ProductsRepository $productsRepository,
        LocationRepository $locationRepository,
    ){
        parent::__construct();
        $this->faker = Factory::create();
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
        $this->vendorsRepository = $vendorsRepository;
        $this->productsRepository = $productsRepository;
        $this->locationRepository = $locationRepository;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    public function getArg(InputInterface $input, int $value = 1): int
    {
        return false === empty($input->getArgument('arg1'))
            ? $input->getArgument('arg1')
            : $value;
    }

    public function getRandomUserId(): int
    {
        $users = array_column($this->employeeRepository->findOnlyIds(), 'id');
        $user = array_rand($users);
        return $users[$user];
    }

    public function getRandomProductId(): int
    {
        $users = array_column($this->productsRepository->findOnlyIds(), 'id');
        $user = array_rand($users);
        return $users[$user];
    }

    public function getRandomVendorId(): int
    {
        $users = array_column($this->vendorsRepository->findOnlyIds(), 'id');
        $user = array_rand($users);
        return $users[$user];
    }

    public function getRandomLocationId(): int
    {
        $users = array_column($this->locationRepository->findOnlyIds(), 'id');
        $user = array_rand($users);
        return $users[$user];
    }
}