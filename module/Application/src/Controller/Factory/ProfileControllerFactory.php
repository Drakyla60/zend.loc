<?php

namespace Application\Controller\Factory;

use Application\Controller\ProfileController;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Service\Admin\ImageManager;
use Application\Service\Admin\UserManager;

class ProfileControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ProfileController
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $imageManager = $container->get(ImageManager::class);

        return new ProfileController($authService, $entityManager, $userManager, $imageManager);
    }
}