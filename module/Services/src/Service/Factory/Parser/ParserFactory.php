<?php

namespace Services\Service\Factory\Parser;

use Services\Service\Parser\Parser;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ParserFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $mongoManager     = $container->get('doctrine.documentmanager.odm_default');
        $entityManager    = $container->get('doctrine.entitymanager.orm_default');

        return new Parser($mongoManager, $entityManager);
    }
}