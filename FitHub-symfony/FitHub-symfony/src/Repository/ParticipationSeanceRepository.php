<?php

namespace App\Repository;

use App\Entity\ParticipationSeance;
use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipationSeance>
 *
 * @method ParticipationSeance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipationSeance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipationSeance[]    findAll()
 * @method ParticipationSeance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationSeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipationSeance::class);
    }

    public function save(ParticipationSeance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParticipationSeance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findMostPopularSeance(): ?Seance
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.seance, COUNT(p.id) as participations')
            ->groupBy('p.seance')
            ->orderBy('participations', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();
        return $result ? $result['seance'] : null;
    }
//    /**
//     * @return ParticipationSeance[] Returns an array of ParticipationSeance objects
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

//    public function findOneBySomeField($value): ?ParticipationSeance
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
