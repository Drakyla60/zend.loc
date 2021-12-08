<?php
namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use User\Entity\User;

/**
 * This is the custom repository class for User entity.
 */
class UserRepository extends EntityRepository
{
    /**
     * Retrieves all users in descending dateCreated order.
     */
    public function findAllUsers(): Query
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->orderBy('u.dateCreated', 'DESC');

        return $queryBuilder->getQuery();
    }

    /**
     * Метод вибирає користувачів які можуть бути авторами постів
     * @return Query
     */
    public function findUsersWhoCanPost()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        return $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->join('u.roles', 'r')
            ->andWhere('r.id = ?2')
            ->setParameter('2', 3)
            ->getQuery()->getResult();
    }

    public function findCountUsers()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        return $queryBuilder->select('count(u.id)')
            ->from(User::class, 'u')
            ->getQuery()->getSingleScalarResult();
    }

}