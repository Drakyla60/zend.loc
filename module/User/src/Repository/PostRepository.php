<?php

namespace User\Repository;

use User\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PostRepository extends EntityRepository
{
    public function findPostsHavingAnyTag(): Query
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);

        return $queryBuilder->getQuery();
    }

    public function findPostsHavingAnyTagArray()
    {
        $array = $this->findPostsHavingAnyTag();
        return $array->getResult();
    }

    public function findPostsByTag($tagName): Query
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->andWhere('t.name = ?2')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED)
            ->setParameter('2', $tagName);

        return $queryBuilder->getQuery();
    }

    public function findPostsByTagArray($tagName)
    {
        $array = $this->findPostsByTag($tagName);
        return $array->getResult();
    }

    /**
     * @return Query
     */
    public function findPublishedPosts(): Query
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);

        return $queryBuilder->getQuery();
    }

    public function findAllPosts()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->orderBy('p.dateCreated', 'DESC');

        return $queryBuilder->getQuery();
    }

    public function findCountPosts()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        return $queryBuilder->select('count(p.id)')
            ->from(Post::class, 'p')
            ->getQuery()->getSingleScalarResult();
    }
}