<?php

namespace Application\Controller\Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Admin\Controller\AuthController;
use Application\Service\Admin\AuthManager;
use Application\Service\Admin\ReCaptchaManager;
use Application\Service\Admin\UserManager;

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