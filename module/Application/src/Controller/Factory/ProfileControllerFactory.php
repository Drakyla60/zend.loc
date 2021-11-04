<?php

namespace Application\Controller\Factory;

use Application\Controller\ProfileController;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ProfileControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ProfileController($authService, $entityManager);
    }
}