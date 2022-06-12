<?php

namespace Application\Service\Admin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Application\Service\Admin\PermissionManager;
use Application\Service\Admin\RbacManager;

class PermissionManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $rbacManager = $container->get(RbacManager::class);

        return new PermissionManager($entityManager, $rbacManager);
    }
}