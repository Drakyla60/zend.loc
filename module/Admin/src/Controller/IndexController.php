<?php

declare(strict_types=1);

namespace Admin\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\SessionManager;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{

    private $sessionContainer;

    public function __construct($sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }

    public function indexAction(): ViewModel
    {
        $this->sessionContainer->myVar = 'Some data';

        $name = $this->sessionContainer;

        $myVar = $sessionContainer->myVar ?? null;

        unset($sessionContainer->myVar);

        return new ViewModel([]);
    }


}
