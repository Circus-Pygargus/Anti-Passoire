<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Category::class);
        $this->security = $security;
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQueryBuilderForSearcherType(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.categoryGroup', 'cg')
            ->leftJoin('cg.creator', 'creator')
            ->leftJoin('cg.users', 'user')

            ->where('cg.slug LIKE :categoryGroupPrivateSlug AND creator = :user')
            ->orWhere('cg.slug = :categoryGroupPublicSlug')
            ->orWhere('user = :user')

            ->setParameter('categoryGroupPrivateSlug', 'prive-%')
            ->setParameter('categoryGroupPublicSlug', 'public')
            ->setParameter('user', $this->security->getUser())

            ->orderBy('cg.label', 'ASC')
            ->addOrderBy('c.label', 'ASC');
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
