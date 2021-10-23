<?php

namespace User\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthAdapter
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AuthAdapter($entityManager);
    }
}