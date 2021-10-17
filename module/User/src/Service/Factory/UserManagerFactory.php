<?php

namespace User\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Model\ViewModel;
use Psr\Container\ContainerInterface;
use User\Service\UserManager;

class UserManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): UserManager
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get(ViewModel::class);

        // Инстанцируем сервис и внедряем зависимости.
        return new UserManager($entityManager, $viewRenderer);
    }
}