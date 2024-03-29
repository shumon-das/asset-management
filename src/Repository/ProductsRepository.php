<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function save(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Products[] Returns an array of Products objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getUniqueManufacturer(): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.manufacturer')->distinct()
            ->andWhere('p.isDeleted = false')
            ->orWhere('p.isDeleted IS null')
            ->groupBy('p.manufacturer')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIds(): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id as id')
            ->addSelect('p.name')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTypeIds(): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id as id')
            ->addSelect('p.type')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCategoryIds(): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id as id')
            ->addSelect('p.category')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findOnlyIds(): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id as id')
            ->getQuery()
            ->getResult()
        ;
    }
}
