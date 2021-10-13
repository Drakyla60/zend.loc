<?php

namespace Application\Service;

use Application\Entity\Post;
use Application\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Laminas\Filter\StaticFilter;
use Laminas\Filter\StringTrim;

class PostManager
{
    private EntityManager $entityManager;

    public function __construct($entityManager)
    {

        $this->entityManager = $entityManager;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function addNewPost($data)
    {
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);
        $post->setDateCreated(date('Y-m-d H:i:s'));

        $this->entityManager->persist($post);

        $this->addTagsToPost($data['tags'], $post);
        
        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function updatePost($post, $data)
    {
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setStatus($data['status']);

        $this->addTagsToPost($data['tags'], $post);

        $this->entityManager->flush();
    }

    /**
     * @throws ORMException
     */
    private function addTagsToPost(string $tagsStr, Post $post)
    {
        $tags = $post->getTags();
        foreach ($tags as $tag) {
            $post->removeTagAssociation($tag);
        }

        $tags = explode(',', $tagsStr);
        foreach ($tags as $tagName){
            $tagName = StaticFilter::execute($tagName, StringTrim::class);

            if (empty($tagName)) continue;

            $tag = $this
                ->entityManager
                ->getRepository(Tag::class)
                ->findOneByName($tagName);

            if (null == $tag)
                $tag = new Tag();

            $tag->setName($tagName);
            $tag->addPost($post);

            $this->entityManager->persist($tag);

            $post->addTag($tag);
        }
    }


    /**
     * @param $post
     * @return string
     */
    public function convertTagsToString($post): string
    {
        $tags = $post->getTags();
        $tagCount = count($tags);
        $tagsStr = '';
        $i = 0;
        foreach ($tags as $tag) {
            $i ++;
            $tagsStr .= $tag->getName();
            if ($i < $tagCount)
                $tagsStr .= ', ';
        }

        return $tagsStr;
    }
}