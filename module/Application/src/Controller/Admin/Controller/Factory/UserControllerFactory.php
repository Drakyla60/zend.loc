<?php

namespace Application\Controller\Admin\Controller\Factory;

use Application\Service\Admin\ImageManager;
use Application\Controller\Admin\Controller\UserController;
use Interop\Container\ContainerInterface;
use Application\Service\Admin\ReCaptchaManager;
use Application\Service\Admin\UserManager;

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