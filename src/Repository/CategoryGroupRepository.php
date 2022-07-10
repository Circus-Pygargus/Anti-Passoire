<?php

namespace App\Repository;

use App\Entity\CategoryGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<CategoryGroup>
 *
 * @method CategoryGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryGroup[]    findAll()
 * @method CategoryGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryGroupRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, CategoryGroup::class);
        $this->security = $security;
    }

    public function add(CategoryGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllForUser(): array
    {
        $query = $this->getQueryBuilderForCategoryEdition();

        return $query->getQuery()->getresult();
    }

    private function getQueryBuilderForCategoryEdition(): QueryBuilder
    {
        return $this->createQueryBuilder('cg')
            ->leftJoin('cg.users', 'user')
            ->where('cg.slug LIKE :categoryGroupPrivateSlug AND cg.creator = :user')
            ->orWhere('cg.slug LIKE :categoryGroupPublicSlug')
            ->orWhere('user = :user')
            ->setParameter('categoryGroupPrivateSlug', 'prive-%')
            ->setParameter('categoryGroupPublicSlug', 'public')
            ->setParameter('user', $this->security->getUser())
            ->orderBy('cg.label', 'ASC');
    }

//    /**
//     * @return Type[] Returns an array of Type objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Type
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
