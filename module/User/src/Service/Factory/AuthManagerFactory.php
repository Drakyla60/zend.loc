<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use User\Service\AuthAdapter;
use User\Service\AuthManager;

class AuthManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthManager
    {
        $sessionManager = $container->get('UserRegistration');
        $authService = $container->get(AuthAdapter::class);

        // Инстанцируем сервис и внедряем зависимости.
        return new AuthManager($authService, $sessionManager);

    }
}