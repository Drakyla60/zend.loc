<?php

namespace Application\Service\Admin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Service\Admin\MailManager;

class MailManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('Config');

        return new MailManager($config);
    }
}