<?php

namespace Application\Controller\Admin\Controller\Factory;

use Application\Controller\Admin\Controller\PostController;
use Application\Service\ImageManager;
use Application\Service\Admin\PostManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Service\Admin\UserManager;

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