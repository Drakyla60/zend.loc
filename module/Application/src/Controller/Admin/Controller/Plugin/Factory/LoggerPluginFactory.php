<?php

namespace Application\Controller\Admin\Controller\Plugin\Factory;

use Application\Controller\Admin\Controller\Plugin\LoggerPlugin;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Application\Service\Admin\LoggerManager;

class LoggerPluginFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $loggerManager = $container->get(LoggerManager::class);

        return new LoggerPlugin($loggerManager);
    }
}