<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use User\Entity\Tag;

class PostTagController extends AbstractActionController
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function indexAction()
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        var_dump($tags);
        return '';
    }

    public function addAction()
    {

        $postTag = new Tag();
        $postTag->setName('Новий Тег');

        $this->entityManager->persist($postTag);

        $this->entityManager->flush();


         var_dump($postTag);
        return '';
    }
}