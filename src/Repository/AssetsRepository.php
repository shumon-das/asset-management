<?php

namespace App\Repository;

use App\Entity\Assets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Assets>
 *
 * @method Assets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assets[]    findAll()
 * @method Assets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assets::class);
    }

    public function save(Assets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Assets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Assets[] Returns an array of Assets objects
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

//    public function findOneBySomeField($value): ?Assets
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
        return $this->createQueryBuilder('a')
            ->select('a.id as id')
            ->addSelect('a.assetName as name')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAssetsBetweenDate($date1, $date2): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.warrantyExpiryDate BETWEEN :date1 AND :date2')
            ->setParameters(['date1' => $date1, 'date2' => $date2])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
