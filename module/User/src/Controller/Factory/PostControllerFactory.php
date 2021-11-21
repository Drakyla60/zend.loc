<?php

namespace User\Controller\Factory;

use User\Controller\PostController;
use User\Service\ImageManager;
use User\Service\PostManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Service\UserManager;

class PostControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        $userManager = $container->get(UserManager::class);
        $imageManager = $container->get(ImageManager::class);

        return new PostController($entityManager, $postManager, $userManager, $imageManager);
    }
}