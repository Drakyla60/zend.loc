<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use User\Entity\PostCategory;

class PostCategoryController extends AbstractActionController
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function indexAction()
    {
        $category = $this->entityManager->getRepository(PostCategory::class)->findAll();
        var_dump($category);
        return '';
    }

    public function addAction()
    {

        $postCategory = new PostCategory();
        $postCategory->setCategoryName('Категорія 2');
        $postCategory->setCategoryDescription('Description Category 2');
        $postCategory->setCategoryActive(PostCategory::CATEGORY_PUBLISHED);
        $postCategory->setCategoryDateCreated(date('Y-m-d H:i:s'));

        $this->entityManager->persist($postCategory);

        $this->entityManager->flush();


//         var_dump($postCategory);
         return '';
    }
}