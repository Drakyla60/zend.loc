<?php

declare(strict_types=1);

namespace Services\Controller;

use Services\Entity\Post;
use Services\Service\Parser\ParseInterface;
use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

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
        $posts = $this->mongoManager
            ->getRepository(Post::class)->findAll();

        var_dump($posts);

        $this->layout()->setTemplate('layout/services_layout');
        return new ViewModel([]);
    }

    public function parseAction()
    {


        try {
            $this->parser->parse();
        } catch (Exception $e) {
            $this->logger('err', 'Error : '. $e->getMessage());
            echo $e->getMessage();
        }

        $this->layout()->setTemplate('layout/services_layout');
        return new ViewModel([]);
    }

    public function importAction()
    {
//        $parse = $this->parser->import();


        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }

}
