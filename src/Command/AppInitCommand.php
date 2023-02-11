<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init',
    description: 'Initialized value for database',
)]
class AppInitCommand extends Command
{
    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $initUser = $this->getApplication()->find('init:user');
        $initUser->run($input, $output);

        $initDepartment = $this->getApplication()->find('init:department');
        $initDepartment->run($input, $output);

        $initProduct = $this->getApplication()->find('add:products');
        $initProduct->run($input, $output);

        $initVendor = $this->getApplication()->find('add:vendors');
        $initVendor->run($input, $output);

        $initLocation = $this->getApplication()->find('add:locations');
        $initLocation->run($input, $output);

        $initAsset = $this->getApplication()->find('add:assets');
        $initAsset->run($input, $output);

        $io->success('Initialized all entity data successfully');

        return Command::SUCCESS;
    }
}
