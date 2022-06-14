<?php

namespace App\Repository;

use App\Entity\AntiPassoire;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AntiPassoire>
 *
 * @method AntiPassoire|null find($id, $lockMode = null, $lockVersion = null)
 * @method AntiPassoire|null findOneBy(array $criteria, array $orderBy = null)
 * @method AntiPassoire[]    findAll()
 * @method AntiPassoire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntiPassoireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AntiPassoire::class);
    }

    public function add(AntiPassoire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AntiPassoire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(?string $keyWords, ?Category $category)
    {
        $query = $this->createQueryBuilder('a');
        if($keyWords != null){
            $query->andWhere('MATCH_AGAINST(a.title, a.text) AGAINST (:keyWords boolean)>0')
                ->setParameter('keyWords', $keyWords);
        }
        if($category != null) {
            $query->innerJoin('a.categories', 'category')
                ->andWhere('category = :category')
                ->setParameter('category', $category)
            ;
        }
        return $query->getQuery()->getResult();
    }

//    /**
//     * @return AntiPassoire[] Returns an array of AntiPassoire objects
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

//    public function findOneBySomeField($value): ?AntiPassoire
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
