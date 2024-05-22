<?php

namespace App\Repository;

use App\Entity\Pdf;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pdf>
 *
 * @method Pdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pdf[]    findAll()
 * @method Pdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pdf::class);
    }

    //    /**
    //     * @return Pdf[] Returns an array of Pdf objects
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

    //    public function findOneBySomeField($value): ?Pdf
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function countUserPdfsForToday(User $user): int
    {
        $today = (new \DateTimeImmutable())->setTime(0, 0);

        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.user = :user')
            ->andWhere('p.created_at >= :today')
            ->setParameter('user', $user)
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByUserOrderedByDate(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
