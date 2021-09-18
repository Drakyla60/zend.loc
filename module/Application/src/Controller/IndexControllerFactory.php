<?php

namespace Application\Controller;

use Application\Service\MailSender;
use Interop\Container\ContainerInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
        $mailSender = $container->get(MailSender::class);

        return new IndexController($mailSender);
    }
}