<?php

namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\PostCategory;

class PostCategoryRepository extends EntityRepository
{
    public function findAllCategoryAsArray()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $posts = $queryBuilder
            ->select('c')
            ->from(PostCategory::class, 'c')
            ->getQuery()->execute();

        $data = [];

        foreach ($posts as $post) {
            $data[$post->getCategoryId()] = $post->getCategoryName();
        }
        return $data;
    }

//    public function findCountUsers()
//    {
//        $entityManager = $this->getEntityManager();
//        $queryBuilder = $entityManager->createQueryBuilder();
//
//        return $queryBuilder->select('count(u.id)')
//            ->from(User::class, 'u')
//            ->getQuery()->getSingleScalarResult();
//    }
}