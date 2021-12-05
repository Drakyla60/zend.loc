<?php

declare(strict_types=1);

namespace Admin\Controller;


use Admin\Entity\Post;
use Admin\Service\Parser\ParseInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{
    private $sessionContainer;
    private $mongoManager;
    private $entityManager;
    private ParseInterface $parser;

    public function __construct($sessionContainer, $mongoManager, $entityManager, $parser)
    {
        $this->sessionContainer = $sessionContainer;
        $this->mongoManager = $mongoManager;
        $this->entityManager = $entityManager;
        $this->parser = $parser;
    }
    public function indexAction(): ViewModel
    {
        $user = $this->mongoManager
            ->getRepository(Post::class)->findAll();

        var_dump($user);
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }

    public function parseAction()
    {
        $parse = $this->parser->parse();
    }

    public function importAction()
    {
        $post = $this->mongoManager
            ->createQueryBuilder(Post::class)
            ->limit(10)
            ->skip(0)
            ->getQuery()
            ->execute()
        ;
        // @TODO Виводить тільки 1 пост а має 10
        var_dump($post);
        die();

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }


}
