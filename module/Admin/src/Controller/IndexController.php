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
    public function indexAction(): ViewModel
    {
        return new ViewModel([]);
    }


}