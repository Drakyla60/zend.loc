<?php

namespace User\Service;

use User\Entity\Comment;
use User\Entity\Post;
use User\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Laminas\Filter\StaticFilter;
use Laminas\Filter\StringTrim;
use User\Entity\User;

class PostManager
{
    private $entityManager;

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
        $user = $this->entityManager
            ->getRepository(User::class)->findOneBy(['id' => $data['author_id']]);

        $post = new Post();
        $post->setAuthor($user);
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setDescription($data['description']);
        $post->setStatus($data['status']);
        $post->setDateCreated(date('Y-m-d H:i:s'));
        $post->setDateUpdated(date('Y-m-d H:i:s'));
        $post->setCountViews(0);
        $post->setImage($data['image']);

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
        $user = $this->entityManager
            ->getRepository(User::class)->findOneBy(['id' => $data['author_id']]);

        $post->setAuthor($user);
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setDescription($data['description']);
        $post->setStatus($data['status']);
        $post->setDateUpdated(date('Y-m-d H:i:s'));
        $post->setCountViews($post->getCountViews() + 1);
        if  (is_string($data['image'])) {
            $post->setImage($data['image']);
        }
        $this->entityManager->persist($post);
        $this->addTagsToPost($data['tags'], $post);

        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function removePost($post)
    {
        $comments = $post->getComments();
        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }

        $tags = $post->getTags();
        foreach ($tags as $tag) {
            $post->removeTagAssociation($tag);
        }

        $this->entityManager->remove($post);

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
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function addCommentToPost($post, $data)
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($data['author']);
        $comment->setContent($data['comment']);
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
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

    /**
     * @param $post
     * @return string
     */
    public function getCommentCountStr($post): string
    {
        $commentCount = count($post->getComments());
        if ($commentCount == 0)
            return 'No comments';
        else if ($commentCount == 1)
            return '1 comment';
        else
            return $commentCount . ' comments';
    }

    /**
     * @param $post
     * @return string
     */
    public function getPostStatusAsString($post): string
    {
        switch ($post->getStatus()) {
            case Post::STATUS_DRAFT: return 'Чорнивик';
            case Post::STATUS_PUBLISHED: return 'Опубліковано';
        }

        return 'Unknown';
    }

    public function getTagCloud(): array
    {
        $tagCloud = [];
        $posts = $this
            ->entityManager
            ->getRepository(Post::class)
            ->findPostsHavingAnyTagArray();

        $totalPostCount = count($posts);

        $tags = $this
            ->entityManager
            ->getRepository(Tag::class)
            ->findAll();

        foreach ($tags as $tag) {

            $postsByTag = $this
                ->entityManager
                ->getRepository(Post::class)
                ->findPostsByTagArray($tag->getName());

            $postCount = count($postsByTag);

            if ($postCount > 0) {
                $tagCloud[$tag->getName()] = $postCount;
            }
        }
        $normalizedTagCloud = [];

        foreach ($tagCloud as $name => $postCount) {
            $normalizedTagCloud[$name] =  $postCount / $totalPostCount;
        }

        return $normalizedTagCloud;
    }


}









