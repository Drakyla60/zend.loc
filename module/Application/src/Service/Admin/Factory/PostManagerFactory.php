<?php

namespace Application\Service\Admin\Factory;

use Application\Service\Admin\PostManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PostManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostManager
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Инстанцируем сервис и внедряем зависимости.
        return new PostManager($entityManager);
    }
}