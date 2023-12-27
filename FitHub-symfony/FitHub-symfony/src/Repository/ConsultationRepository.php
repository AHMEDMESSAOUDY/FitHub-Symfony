<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Query\Mysql\Month;

/**
 * @extends ServiceEntityRepository<Consultation>
 *
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }

    public function save(Consultation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Consultation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



//     public function findByMultiple($searchValue)
// {
//     return $this->createQueryBuilder('s')
//         //->join('s.utilisateur', 'c')
//         //->addSelect('c')
//         ->where('s.nom LIKE :nom')
//         ->orWhere('s.typeConsultation LIKE :type')
//         ->orWhere('s.dateConsultation LIKE :date')
//        // ->orWhere('c.nom LIKE :nomu')
//         ->setParameters(
//             ['nom' => $searchValue, 'type'=>$searchValue,
//                 'date'=>$searchValue, //'nomu'=>$searchValue
//             ])
//         ->getQuery()
//         ->getResult();
// }


public function findByPeriod($monday,$sunday)
{
    return $this->createQueryBuilder('s')
    ->where('s.dateConsultation BETWEEN :monday AND :sunday')
    ->setParameter('monday', $monday)
    ->setParameter('sunday', $sunday)
    ->getQuery()
    ->getResult();
}


public function getConsultationByYear()
{
    $qb = $this->createQueryBuilder('s');
    $qb->select("COUNT(s.id) as count, DATE_FORMAT(s.dateConsultation, '%Y') as year")
        ->groupBy('year');

    return $qb->getQuery()->getResult();
}

public function getConsultationByMonth()
{
    $qb = $this->createQueryBuilder('s');
    $qb->select("COUNT(s.id) as count, DATE_FORMAT(s.dateConsultation, '%Y-%m') as month")
        ->groupBy('month');

    return $qb->getQuery()->getResult();
}

public function getConsultationByType()
{
    $qb = $this->createQueryBuilder('s');
    $qb->select("COUNT(s.id) as count, s.typeConsultation as type")
        ->groupBy('type');

    return $qb->getQuery()->getResult();
}

public function findByDate($monday)
{
    return $this->createQueryBuilder('s')
    ->where('s.dateConsultation LIKE :monday')
    ->setParameter('monday', $monday)
    ->getQuery()
    ->getResult();
}



    // public function findEntitiesByString($str){
    //     return $this->getEntityManager()
    //         ->createQuery(
    //             'SELECT e
    //             FROM AppBundle:Entity e
    //             WHERE e.foo LIKE :str'
    //         )
    //         ->setParameter('str', '%'.$str.'%')
    //         ->getResult();
    // }

   /**
    * @return Consultation[] Returns an array of Consultation objects
    */
   public function findBySearch($data): array
   {
        $qb = $this->createQueryBuilder('c')
           ->where('c.nom LIKE :keyword')
           ->setParameter('keyword', '%' . $data['nom'] . '%');
        
        if ($data['typeConsultation'])
        {
            $qb->andWhere('c.typeConsultation = :typeConsultation')
            ->setParameter('typeConsultation', $data['typeConsultation']);
        }
        if ($data['dateConsultation'])
        {
            $qb->andWhere('c.dateConsultation = :dateConsultation')
            ->setParameter('dateConsultation', $data['dateConsultation']);
        }

        return $qb->getQuery()->getResult();
   }

   public function findByFilterA(): array
   {
        $qb = $this->createQueryBuilder('c')
        ->orderBy('c.nom', 'ASC');
        return $qb->getQuery()->getResult();
   }

   public function findByFilterD(): array
   {
        $qb = $this->createQueryBuilder('c')
        ->orderBy('c.nom', 'DESC');
        return $qb->getQuery()->getResult();
   }

   public function findByDateA(): array
   {
        $qb = $this->createQueryBuilder('c')
        ->orderBy('c.dateConsultation', 'ASC');
        return $qb->getQuery()->getResult();
   }

   public function findByDateD(): array
   {
        $qb = $this->createQueryBuilder('c')
        ->orderBy('c.dateConsultation', 'DESC');
        return $qb->getQuery()->getResult();
   }


//    public function findOneBySomeField($value): ?Consultation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
