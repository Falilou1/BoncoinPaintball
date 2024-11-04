<?php

namespace App\Repository;

use App\Entity\Adverts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;


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

    public function findBySomeField(
        ?string $category,
        ?string $brand,
        ?string $description,
        ?string $region,
        ?string $useCondition
    ): QueryBuilder {
        $query = $this->createQueryBuilder('a');
    
        if ($category) {
            $query->andWhere('a.category = :category')
                  ->setParameter('category', $category);
        }
        if ($brand) {
            $query->andWhere('a.brand = :brand')
                  ->setParameter('brand', $brand);
        }
        if ($description) {
            $query->andWhere('a.description = :description')
                  ->setParameter('description', $description);
        }
        if ($region) {
            $query->andWhere('a.region = :region')
                  ->setParameter('region', $region);
        }
        if ($useCondition) {
            $query->andWhere('a.useCondition = :useCondition')
                  ->setParameter('useCondition', $useCondition);
        }
        $query->orderBy('a.id', 'ASC');
    
        return $query; // Assurez-vous de retourner le QueryBuilder
    }
    
}
