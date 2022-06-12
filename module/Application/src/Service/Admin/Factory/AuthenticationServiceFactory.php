<?php

namespace Application\Service\Admin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Laminas\Authentication\Storage\Session as SessionStorage;
use Application\Service\Admin\AuthAdapter;

class AuthenticationServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthenticationService
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new SessionStorage('UserSessionContainer', 'session', $sessionManager);
        $authAdapter = $container->get(AuthAdapter::class);

        return new AuthenticationService($authStorage, $authAdapter);
    }
}