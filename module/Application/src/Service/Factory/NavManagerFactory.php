<?php

namespace Application\Service\Factory;

use Application\Service\NavManager;
use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;
use Admin\Service\RbacManager;

class NavManagerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);

        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $rbacManager = $container->get(RbacManager::class);

        return new NavManager($authService, $urlHelper, $rbacManager);
    }
}