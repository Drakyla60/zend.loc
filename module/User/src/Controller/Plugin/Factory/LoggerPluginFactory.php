<?php

namespace User\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\Plugin\LoggerPlugin;
use User\Service\LoggerManager;

class LoggerPluginFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $loggerManager = $container->get(LoggerManager::class);

        return new LoggerPlugin($loggerManager);
    }
}