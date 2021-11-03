<?php

namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Service\MailManager;

class MailManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('Config');

        return new MailManager($config);
    }
}