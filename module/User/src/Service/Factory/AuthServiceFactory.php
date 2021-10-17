<?php

namespace User\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Laminas\Session\Storage\SessionStorage;
use Psr\Container\ContainerInterface;
use User\Service\AuthAdapter;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthenticationService
    {
        $sessionManager = $container->get('UserRegistration');
        $authAdapter = $container->get(AuthAdapter::class);

        // Создаем сервис и внедряем зависимости в его конструктор.
        return new AuthenticationService($sessionManager, $authAdapter);
    }
}