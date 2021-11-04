<?php

namespace Application\Controller\Factory;

use Application\Controller\RegistrationController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class RegistrationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionContainer = $container->get('UserRegistrations');

        // Инстанцируем контроллер и внедряем зависимости.
        return new RegistrationController($sessionContainer);
    }
}