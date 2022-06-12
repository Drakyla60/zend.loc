<?php

namespace Application\Service\Admin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Model\ViewModel;
use Psr\Container\ContainerInterface;
use Application\Service\Admin\MailManager;
use Application\Service\Admin\PermissionManager;
use Application\Service\Admin\RoleManager;
use Application\Service\Admin\UserManager;

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