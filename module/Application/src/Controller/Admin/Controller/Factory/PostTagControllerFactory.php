<?php

namespace Application\Controller\Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Admin\Controller\PostTagController;
use Application\Service\Admin\PostTagManager;

class PostTagControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostTagController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $tagManager = $container->get(PostTagManager::class);

        return new PostTagController($entityManager, $tagManager);
    }
}