<?php

namespace App\Repository;

use App\Entity\PersonLikeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonLikeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonLikeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonLikeProduct[]    findAll()
 * @method PersonLikeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonLikeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonLikeProduct::class);
    }

    /**
     * @param array $parameters
     * @return QueryBuilder
     */
    public function search(array $parameters): QueryBuilder
    {
        $queryBuilder = $this->getEntityManager()->getRepository(PersonLikeProduct::class)
            ->createQueryBuilder('p');
        $queryBuilder->select([
            'p.person_id as person_id',
            'p.product_id as product_id',
            "CONCAT(pe.f_name, ' ', pe.l_name) as person_name",
            'pr.name as product_name'
        ]);

        $queryBuilder->leftJoin('p.person', 'pe');
        $queryBuilder->leftJoin('p.product', 'pr');

        if (!empty($parameters['person_id'])) {
            $id = $parameters['person_id'];
            $queryBuilder->andWhere($queryBuilder->expr()->eq('p.person_id', ':person_id'))
                ->setParameter('person_id', $id);
        }

        if (!empty($parameters['product_id'])) {
            $id = $parameters['product_id'];
            $queryBuilder->andWhere($queryBuilder->expr()->eq('p.product_id', ':product_id'))
                ->setParameter('product_id',  $id);
        }

        if (!empty($parameters['person_name'])) {
            $name = $parameters['person_name'];
            $queryBuilder->andWhere($queryBuilder->expr()->like("CONCAT(pe.f_name, ' ', pe.l_name)", ':person_name'))
                ->setParameter('person_name', '%' . $name . '%');
        }

        if (!empty($parameters['product_name'])) {
            $name = $parameters['product_name'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('pr.name', ':product_name'))
                ->setParameter('product_name', '%' . $name . '%');
        }

        if (!empty($parameters['sort_field'])) {
            $sortField = 'p.' . $parameters['sort_field'];
        } else {
            $sortField = 'p.person_id';
        }

        if (isset($parameters['sort_direction']) && $parameters['sort_direction'] == 'ASC') {
            $sortDirection = $parameters['sort_direction'];
        } else {
            $sortDirection = 'DESC';
        }

        $queryBuilder->orderBy($sortField, $sortDirection);

        return $queryBuilder;
    }

    // /**
    //  * @return PersonLikeProduct[] Returns an array of PersonLikeProduct objects
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
    public function findOneBySomeField($value): ?PersonLikeProduct
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
