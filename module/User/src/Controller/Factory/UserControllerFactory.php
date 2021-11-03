<?php

namespace User\Controller\Factory;

use User\Controller\UserController;
use Interop\Container\ContainerInterface;
use User\Service\ReCaptchaManager;
use User\Service\UserManager;

class UserControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): UserController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $reCaptchaManager = $container->get(ReCaptchaManager::class);
        $sessionContainer = $container->get('UserSessionContainer');

        return new UserController($entityManager, $userManager, $reCaptchaManager, $sessionContainer);
    }
}