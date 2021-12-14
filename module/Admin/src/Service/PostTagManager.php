<?php

namespace Admin\Service;

use Admin\Entity\Tag;

class PostTagManager
{

    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addTag($data)
    {
        $postTag = new Tag();
        $postTag->setName($data['post-tag-name']);

        $this->entityManager->persist($postTag);

        $this->entityManager->flush();
    }

    public function updateTag($postTag, $data)
    {
        $postTag->setName($data['post-tag-name']);

        $this->entityManager->persist($postTag);

        $this->entityManager->flush();
    }

    public function removeTag($data)
    {
        $data->setDateDeleted(date('Y-m-d H:i:s'));

        $this->entityManager->persist($data);

        $this->entityManager->flush();
    }

    /**
     * @param $data
     */
    public function restoreTag($data)
    {
        $data->setDateDeleted(null);

        $this->entityManager->persist($data);

        $this->entityManager->flush();
    }


}