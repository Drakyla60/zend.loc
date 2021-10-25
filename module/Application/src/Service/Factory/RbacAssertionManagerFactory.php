<?php

namespace Application\Service\Factory;

use Application\Service\RbacAssertionManager;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RbacAssertionManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService =  $container->get(AuthenticationService::class);

        return new RbacAssertionManager($entityManager, $authService);
    }
}