<?php

namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\PostTagController;
use User\Service\PostTagManager;

class PostTagControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostTagController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tagManager = $container->get(PostTagManager::class);

        return new PostTagController($entityManager, $tagManager);
    }
}