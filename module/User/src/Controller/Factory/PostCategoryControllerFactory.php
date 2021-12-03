<?php

namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\PostCategoryController;

class PostCategoryControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostCategoryController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new PostCategoryController($entityManager);
    }
}