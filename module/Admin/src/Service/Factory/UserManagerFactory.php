<?php

namespace Admin\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Model\ViewModel;
use Psr\Container\ContainerInterface;
use Admin\Service\MailManager;
use Admin\Service\PermissionManager;
use Admin\Service\RoleManager;
use Admin\Service\UserManager;

class UserManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): UserManager
    {
        $entityManager      = $container->get('doctrine.entitymanager.orm_default');
        $roleManager        = $container->get(RoleManager::class);
        $mailManager        = $container->get(MailManager::class);
        $permissionManager  = $container->get(PermissionManager::class);
        $viewRenderer       = $container->get('ViewRenderer');
        $config             = $container->get('Config');

        return new UserManager(
            $entityManager,
            $roleManager,
            $mailManager,
            $permissionManager,
            $viewRenderer,
            $config);
    }
}