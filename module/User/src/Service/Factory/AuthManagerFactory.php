<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use User\Service\AuthManager;
use User\Service\RbacManager;

class AuthManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthManager
    {
        $authService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);
        $rbacManager = $container->get(RbacManager::class);

        // Get contents of 'access_filter' config key (the AuthManager service
        // will use this data to determine whether to allow currently logged in user
        // to execute the controller action or not.
        $config = $container->get('Config');
        $config = $config['access_filter'] ?? [];

        return new AuthManager($authService, $sessionManager, $config, $rbacManager);

    }
}