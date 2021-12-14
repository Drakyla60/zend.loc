<?php

namespace Admin\Controller\Factory;

use Admin\Service\ImageManager;
use Admin\Controller\UserController;
use Interop\Container\ContainerInterface;
use Admin\Service\ReCaptchaManager;
use Admin\Service\UserManager;

class UserControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): UserController
    {
        $entityManager    = $container->get('doctrine.entitymanager.orm_default');
        $userManager      = $container->get(UserManager::class);
        $reCaptchaManager = $container->get(ReCaptchaManager::class);
        $sessionContainer = $container->get('UserSessionContainer');
        $imageManager     = $container->get(ImageManager::class);

        return new UserController(
            $entityManager,
            $userManager,
            $reCaptchaManager,
            $sessionContainer,
            $imageManager
        );
    }
}