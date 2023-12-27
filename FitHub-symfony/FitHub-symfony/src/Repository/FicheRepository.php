<?php

namespace App\Repository;

use App\Entity\Fiche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fiche>
 *
 * @method Fiche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fiche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fiche[]    findAll()
 * @method Fiche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiche::class);
    }

    public function save(Fiche $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fiche $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFicheBylength()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select("COUNT(s.id) as count, length(s.descriptionFiche) as length")
            ->groupBy('length');
    
        return $qb->getQuery()->getResult();
    }

    public function getFicheByCategory()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select("COUNT(s.id) as count, s.category as category")
            ->groupBy('category');
    
        return $qb->getQuery()->getResult();
    }

    public function findByFilterA(): array
    {
         $qb = $this->createQueryBuilder('c')
         ->orderBy('c.nomFiche', 'ASC');
         return $qb->getQuery()->getResult();
    }
 
    public function findByFilterD(): array
    {
         $qb = $this->createQueryBuilder('c')
         ->orderBy('c.nomFiche', 'DESC');
         return $qb->getQuery()->getResult();
    }
    
//    /**
//     * @return Fiche[] Returns an array of Fiche objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fiche
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
