<?php

namespace Application\Controller\Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Admin\Controller\RoleController;
use Application\Service\Admin\RoleManager;

class RoleControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $roleManager = $container->get(RoleManager::class);

        // Instantiate the controller and inject dependencies
        return new RoleController($entityManager, $roleManager);
    }
}