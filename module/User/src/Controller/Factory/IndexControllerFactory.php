<?php

namespace User\Controller\Factory;

use User\Controller\IndexController;
use Interop\Container\ContainerInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
//        $sessionContainer = $container->get('ContainerNamespace');
        return new IndexController();
    }
}