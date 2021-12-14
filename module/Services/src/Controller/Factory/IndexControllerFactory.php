<?php

namespace Services\Controller\Factory;

use Services\Controller\IndexController;
use Services\Service\Parser\Parser;
use Services\Service\Parser\TrelloParser;
use Interop\Container\ContainerInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
        $sessionContainer = $container->get('ContainerNamespace');
        $mongoManager     = $container->get('doctrine.documentmanager.odm_default');
        $entityManager    = $container->get('doctrine.entitymanager.orm_default');
        $parser           = $container->get(Parser::class);
        $trelloParser     = $container->get(TrelloParser::class);
        return new IndexController(
            $sessionContainer,
            $mongoManager,
            $entityManager,
            $parser,
            $trelloParser);
    }
}