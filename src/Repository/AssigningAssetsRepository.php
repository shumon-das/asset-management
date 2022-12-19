<?php

namespace App\Repository;

use App\Entity\AssigningAssets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssigningAssets>
 *
 * @method AssigningAssets|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssigningAssets|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssigningAssets[]    findAll()
 * @method AssigningAssets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssigningAssetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssigningAssets::class);
    }

    public function save(AssigningAssets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AssigningAssets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AssigningAssets[] Returns an array of AssigningAssets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssigningAssets
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findIds(): ?array
    {
        return $this->createQueryBuilder('aa')
            ->select('aa.id as id')
            ->getQuery()
            ->getResult()
        ;
    }
}
