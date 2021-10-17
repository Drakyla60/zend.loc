<?php

namespace Admin\Controller\Factory;

use Admin\Controller\IndexController;
use Interop\Container\ContainerInterface;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
//        $mailSender = $container->get(MailSender::class);
//        $entityManager = $container->get('doctrine.entitymanager.orm_default');
//        $postManager = $container->get(PostManager::class);

//        return new IndexController($mailSender, $entityManager, $postManager);
        return new IndexController();
    }
}