<?php

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Admin\Controller\PostCategoryController;
use Admin\Service\PostCategoryManager;

class PostCategoryControllerFactory implements FactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostCategoryController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postCategoryManager = $container->get(PostCategoryManager::class);

        return new PostCategoryController($entityManager, $postCategoryManager);
    }
}