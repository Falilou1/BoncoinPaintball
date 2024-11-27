<?php

namespace App\Repository;

use App\Entity\Adverts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Adverts>
 *
 * @method Adverts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adverts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adverts[]    findAll()
 * @method Adverts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adverts::class);
    }

    public function add(Adverts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Adverts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

  // /**
    //  * @return Advert[] Returns an array of Advert objects
    //  */
    public function findByUserAndStatus(int $userId, string $status)/**@phpstan-ignore-line */
    {
        $builder = $this->createQueryBuilder('a')
            ->andWhere('a.owner = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('a.id', 'ASC')
        ;

        if ($status != 'Toutes') {
            $builder
                ->andWhere('a.status = :status')
                ->setParameter('status', $status)
            ;
        }

        return $builder
            ->getQuery()
            ->getResult()
        ;
    }

// /**
    //  * @return Advert[] Returns an array of Advert objects
    //  */
    public function findLastAdverts()/**@phpstan-ignore-line */
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySomeField(?string $category, ?string $brand, ?string $description, ?string $region, ?string $useCondition): ?array
    {
        $qb = $this->createQueryBuilder('a');
    
        // Vérification et ajout des critères de recherche
        if (!empty($category)) {
            $qb->andWhere('a.category = :category')
               ->setParameter('category', $category);
        }
    
        if (!empty($brand)) {
            $qb->andWhere('a.brand = :brand')
               ->setParameter('brand', $brand);
        }
    
        if (!empty($description)) {
            $qb->andWhere('a.description LIKE :description')
               ->setParameter('description', '%' . $description . '%');
        }
    
        if (!empty($region)) {
            $qb->andWhere('a.region = :region')
               ->setParameter('region', $region);
        }
    
        if (!empty($useCondition)) {
            $qb->andWhere('a.useCondition = :useCondition')
               ->setParameter('useCondition', $useCondition);
        }
    
        // Retourne la requête construite
        return $qb->getQuery()->getResult();
    }
    
}
