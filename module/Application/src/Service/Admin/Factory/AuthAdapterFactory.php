<?php

namespace Application\Service\Admin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Application\Service\Admin\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthAdapter
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AuthAdapter($entityManager);
    }
}