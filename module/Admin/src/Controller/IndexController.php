<?php

declare(strict_types=1);

namespace Admin\Controller;


use Admin\Entity\Post;
use Admin\Service\Parser\ParseInterface;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Filter\StaticFilter;
use Laminas\Filter\StringTrim;
use Laminas\Form\Element\Email;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Entity\Role;
use User\Entity\Tag;
use User\Entity\User;

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
        $posts = $this->mongoManager
            ->getRepository(Post::class)->findAll();

        var_dump($posts);
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }

    public function parseAction()
    {
        $parse = $this->parser->parse();
    }

    public function importAction()
    {
        $parse = $this->parser->import();


        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }




}
