<?php

namespace App\Command;

use App\Entity\Assets;
use App\Entity\Products;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'add:products',
    description: 'create one or many products',
)]
class InitProductsCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = $input->getArgument('arg1') ?? 1;

        for ($i = 0; $i < $this->getArg($input); ++$i) {
            $user = $this->getRandomUserId();
            $product = new Products();
            $product
                ->setName($this->faker->realText(25))
                ->setType($this->faker->realText(10))
                ->setCategory($this->faker->realText(15))
                ->setManufacturer($this->faker->realText(15))
                ->setDescription($this->faker->realText(125))
                ->setIsDeleted(0)
                ->setStatus(true)
                ->setUpdatedAt(null)
                ->setUpdatedBy(null)
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy($user)
                ->setDeletedAt(null)
                ->setDeletedBy(null)
            ;
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }

        $io = new SymfonyStyle($input, $output);
        $io->success("Created $arg Products");

        return Command::SUCCESS;
    }
}
