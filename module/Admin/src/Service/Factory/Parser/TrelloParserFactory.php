<?php

namespace Admin\Service\Factory\Parser;

use Admin\Service\Parser\TrelloParser;
use Composer\Config;
use GuzzleHttp\Client;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

class TrelloParserFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $mongoManager  = $container->get('doctrine.documentmanager.odm_default');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('Config');
        $config = $config['api_keys'];
        $guzzleClient  = new Client();

        return new TrelloParser($mongoManager, $entityManager, $guzzleClient, $config);
    }
}