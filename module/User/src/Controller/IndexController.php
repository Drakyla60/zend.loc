<?php

namespace User\Controller;

use User\Entity\Post;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Entity\User;

class IndexController extends AbstractActionController
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $usersCount = $this->entityManager
            ->getRepository(User::class)->findCountUsers();

        $postsCount = $this->entityManager
            ->getRepository(Post::class)->findCountPosts();

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'usersCount' => $usersCount,
            'postsCount' => $postsCount,
        ]);
    }
}