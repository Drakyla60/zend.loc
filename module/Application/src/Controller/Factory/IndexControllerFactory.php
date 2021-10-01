<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Application\Service\MailSender;
use Interop\Container\ContainerInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
        $mailSender = $container->get(MailSender::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new IndexController($mailSender, $entityManager);
    }
}