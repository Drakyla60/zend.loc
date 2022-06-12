<?php

namespace Application\Controller\Admin\Controller\Plugin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Application\Controller\Admin\Controller\Plugin\AccessPlugin;
use Application\Service\Admin\RbacManager;

class AccessPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AccessPlugin
    {
        $rbacManager = $container->get(RbacManager::class);

        return new AccessPlugin($rbacManager);
    }
}