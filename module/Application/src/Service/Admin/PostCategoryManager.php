<?php

namespace Application\Service\Admin;

use Application\Entity\PostCategory;

class PostCategoryManager
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addPostCategory($data)
    {

        $postCategory = new PostCategory();
        $postCategory->setCategoryName($data['post-category-name']);
        $postCategory->setCategoryDescription($data['post-category-description']);
        $postCategory->setCategoryActive($data['post-category-status']);
        $postCategory->setCategoryDateCreated(date('Y-m-d H:i:s'));

        $this->entityManager->persist($postCategory);

        $this->entityManager->flush();
    }

    public function updatePostCategory($postTag, $data)
    {
        $postTag->setCategoryName($data['post-category-name']);
        $postTag->setCategoryDescription($data['post-category-description']);
        $postTag->setCategoryActive($data['post-category-status']);

        $this->entityManager->persist($postTag);

        $this->entityManager->flush();
    }

    public function removePostCategory($data)
    {
        $data->setCategoryDateDeleted(date('Y-m-d H:i:s'));

        $this->entityManager->persist($data);

        $this->entityManager->flush();
    }

    /**
     * @param $data
     */
    public function restorePostCategory($data)
    {
        $data->setCategoryDateDeleted(null);

        $this->entityManager->persist($data);

        $this->entityManager->flush();
    }
}