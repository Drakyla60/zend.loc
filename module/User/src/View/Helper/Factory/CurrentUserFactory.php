<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use User\View\Helper\CurrentUser;

class CurrentUserFactory
{
    public function __invoke(ContainerInterface $container)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService = $container->get(AuthenticationService::class);
                        
        return new CurrentUser($entityManager, $authService);
    }
}
