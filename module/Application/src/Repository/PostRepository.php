<?php

namespace Application\Repository;

use Application\Entity\Post;
use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findPostsHavingAnyTag()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Post::STATUS_PUBLISHED);

        $posts = $queryBuilder->getQuery()->getResult();

        return $posts;
    }

    public function findPostsByTag($tagName)
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

        $posts = $queryBuilder->getQuery()->getResult();

        return $posts;
    }
}