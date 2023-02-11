<?php

namespace App\Command;

use App\Entity\Assets;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'add:assets',
    description: 'create one or many assets',
)]
class InitAssetsCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = $input->getArgument('arg1') ?? 1;

        for ($i = 0; $i < $this->getArg($input); ++$i) {
            $asset = new Assets();
            $asset
                ->setProduct($this->getRandomProductId())
                ->setVendor($this->getRandomVendorId())
                ->setAssetName($this->faker->realText(25))
                ->setSerialNumber(random_int(1, 1000))
                ->setPrice(random_int(1, 500))
                ->setLocation($this->getRandomLocationId())
                ->setPurchaseDate(new DateTimeImmutable())
                ->setWarrantyExpiryDate(new DateTimeImmutable($this->faker->date()))
                ->setPurchaseType($this->faker->realText(15))
                ->setDescription($this->faker->realText(150))
                ->setUsefulLife('1 year')
                ->setResidualValue($this->faker->realText(15))
                ->setRate(random_int(1, 100))
                ->setIsDeleted(0)
                ->setStatus(true)
                ->setUpdatedAt(null)
                ->setUpdatedBy(null)
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy($this->getRandomUserId())
                ->setDeletedAt(null)
                ->setDeletedBy(null)
            ;
            $this->entityManager->persist($asset);
            $this->entityManager->flush();
        }

        $io = new SymfonyStyle($input, $output);
        $io->success("Created $arg Assets");

        return Command::SUCCESS;
    }
}
