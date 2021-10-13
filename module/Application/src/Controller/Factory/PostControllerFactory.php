<?php

namespace Application\Controller\Factory;

use Application\Controller\PostController;
use Application\Service\PostManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PostControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);

        return new PostController($entityManager, $postManager);
    }
}