<?php

namespace User\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Service\RbacManager;

class RbacManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService = $container->get(AuthenticationService::class);
        $cache = $container->get('FilesystemCache');

        $assertionManagers = [];
        $config = $container->get('Config');
        if (isset($config['rbac_manager']['assertions'])) {
            foreach ($config['rbac_manager']['assertions'] as $serviceName) {
                $assertionManagers[$serviceName] = $container->get($serviceName);
            }
        }

        return new RbacManager($entityManager, $authService, $cache, $assertionManagers);
    }
}