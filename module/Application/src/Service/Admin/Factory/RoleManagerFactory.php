<?php

namespace Application\Service\Admin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Application\Service\Admin\RbacManager;
use Application\Service\Admin\RoleManager;

class RoleManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $rbacManager = $container->get(RbacManager::class);

        return new RoleManager($entityManager, $rbacManager);
    }
}