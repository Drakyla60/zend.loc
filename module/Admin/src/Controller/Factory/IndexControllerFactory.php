<?php

namespace Admin\Controller\Factory;

use Admin\Controller\IndexController;
use Admin\Service\Parser\Parser;
use Interop\Container\ContainerInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
        $sessionContainer = $container->get('ContainerNamespace');
        $mongoManager     = $container->get('doctrine.documentmanager.odm_default');
        $entityManager    = $container->get('doctrine.entitymanager.orm_default');
        $parser           = $container->get(Parser::class);
        return new IndexController($sessionContainer, $mongoManager, $entityManager, $parser);
    }
}