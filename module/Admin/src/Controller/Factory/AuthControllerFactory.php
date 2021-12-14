<?php

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\AuthController;
use Admin\Service\AuthManager;
use Admin\Service\ReCaptchaManager;
use Admin\Service\UserManager;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authManager = $container->get(AuthManager::class);
        $userManager = $container->get(UserManager::class);
        $reCaptchaManager = $container->get(ReCaptchaManager::class);
        $authService = $container->get(AuthenticationService::class);

        return new AuthController($entityManager, $authManager, $userManager, $reCaptchaManager, $authService);
    }
}