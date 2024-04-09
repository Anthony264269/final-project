<?php

namespace App\Repository;

use App\Entity\MyGarage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MyGarage>
 *
 * @method MyGarage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyGarage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyGarage[]    findAll()
 * @method MyGarage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyGarageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MyGarage::class);
    }

    //    /**
    //     * @return MyGarage[] Returns an array of MyGarage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MyGarage
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
