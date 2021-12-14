<?php

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\PostTagController;
use Admin\Service\PostTagManager;

class PostTagControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostTagController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tagManager = $container->get(PostTagManager::class);

        return new PostTagController($entityManager, $tagManager);
    }
}