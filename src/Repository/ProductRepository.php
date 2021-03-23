<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $parameters
     * @return QueryBuilder
     */
    public function search(array $parameters): QueryBuilder
    {
        $queryBuilder = $this->getEntityManager()->getRepository(Product::class)
            ->createQueryBuilder('p');

        if (isset($parameters['id'])) {
            $id = $parameters['id'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.id', ':id'))
                ->setParameter('id', '%' . $id . '%');
        }

        if (isset($parameters['name'])) {
            $name = $parameters['name'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.name', ':name'))
                ->setParameter('name', '%' . $name . '%');
        }
        if (isset($parameters['info'])) {
            $info = $parameters['info'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.info', ':info'))
                ->setParameter('info', '%' . $info . '%');
        }
        if (isset($parameters['date'])) {
            $date = $parameters['date'];
            $queryBuilder->andWhere('p.date = :date')->setParameter('date', $date);
        }

        if (isset($parameters['sort_field'])) {
            $sortField = 'p.' . $parameters['sort_field'];
        } else {
            $sortField = 'p.id';
        }

        if (isset($parameters['sort_direction']) && $parameters['sort_direction'] == 'ASC') {
            $sortDirection = $parameters['sort_direction'];
        } else {
            $sortDirection = 'DESC';
        }

        $queryBuilder->orderBy($sortField, $sortDirection);

        return $queryBuilder;
    }

    public function findByName($name)
    {
        if (!$name) {
            return [];
        }

        $queryBuilder = $this->getEntityManager()->getRepository(Product::class)
            ->createQueryBuilder('p');

        return $queryBuilder
            ->select([
                'p.id as id',
                'p.name as label',
                'p.name as value',
            ])
            ->andWhere($queryBuilder->expr()->like('p.name', ':name'))
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
