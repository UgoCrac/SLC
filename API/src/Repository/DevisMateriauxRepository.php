<?php

namespace App\Repository;

use App\Entity\DevisMateriaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisMateriaux>
 *
 * @method DevisMateriaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevisMateriaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevisMateriaux[]    findAll()
 * @method DevisMateriaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisMateriauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisMateriaux::class);
    }

//    /**
//     * @return DevisMateriaux[] Returns an array of DevisMateriaux objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DevisMateriaux
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
