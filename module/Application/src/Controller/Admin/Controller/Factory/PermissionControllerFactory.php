<?php

namespace Application\Controller\Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Admin\Controller\PermissionController;
use Application\Service\Admin\PermissionManager;

class PermissionControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PermissionController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $permissionManager = $container->get(PermissionManager::class);

        // Instantiate the controller and inject dependencies
        return new PermissionController($entityManager, $permissionManager);
    }
}