<?php

namespace App\Repository;

use App\Entity\CategoryGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $categoryGroups = [];
        $categoryGroups[] = $this->getPublic();
        \array_merge($categoryGroups, $this->getAllForUser());

        return $categoryGroups;
    }

    private function getPublic(): ?CategoryGroup
    {
        $query = $this->createQueryBuilder('cg')
            ->where('cg.slug = :catGroupSlug')
            ->setParameter('catGroupSlug', 'public')
        ;

        return $query->getQuery()->getsingleResult();
    }

    private function getAllForUser(): ?array
    {
        $query = $this->createQueryBuilder('cg');
        // Already checked that user has at least ROLE_CONTRIBUTOR in controller
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            $query->leftJoin('cg.users', 'user')
                ->andWhere('user = :user')
                ->setParameter('user', $this->security->getUser())
            ;
        }

        return $query->getQuery()->getResult();
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
