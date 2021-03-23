<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param array $parameters
     * @return QueryBuilder
     */
    public function search(array $parameters): QueryBuilder
    {
        $queryBuilder = $this->getEntityManager()->getRepository(Person::class)
            ->createQueryBuilder('p');

        if (!empty($formName)) {
            $parameters = $parameters[$formName];
        }

        if (!empty($parameters['id'])) {
            $id = $parameters['id'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.id', ':id'))
                ->setParameter('id', '%' . $id . '%');
        }

        if (!empty($parameters['login'])) {
            $name = $parameters['login'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.login', ':login'))
                ->setParameter('login', '%' . $name . '%');
        }
        if (!empty($parameters['l_name'])) {
            $info = $parameters['l_name'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.l_name', ':l_name'))
                ->setParameter('l_name', '%' . $info . '%');
        }
        if (!empty($parameters['f_name'])) {
            $info = $parameters['f_name'];
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.f_name', ':f_name'))
                ->setParameter('f_name', '%' . $info . '%');
        }
        if (!empty($parameters['state'])) {
            $date = $parameters['state'];
            $queryBuilder->andWhere('p.state = :state')->setParameter('state', $date);
        }

        if (!empty($parameters['sort_field'])) {
            $sortField = 'p.' . $parameters['sort_field'];
        } else {
            $sortField = 'p.id';
        }

        if (!empty($parameters['sort_direction']) && $parameters['sort_direction'] == 'ASC') {
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

        $queryBuilder = $this->getEntityManager()->getRepository(Person::class)
            ->createQueryBuilder('p');

        return $queryBuilder
            ->select([
                'p.id as id',
                "CONCAT(p.f_name, ' ', p.l_name) as label",
                "CONCAT(p.f_name, ' ', p.l_name) as value",
            ])
            ->andWhere($queryBuilder->expr()->like("CONCAT(p.f_name, ' ', p.l_name)", ':person_name'))
            ->setParameter('person_name', '%' . $name . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
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
    public function findOneBySomeField($value): ?Person
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
