<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Application\Service\MailSender;
use Application\Service\PostManager;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use User\Service\MailManager;
use User\Service\ReCaptchaManager;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
        $mailSender = $container->get(MailSender::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        $authService = $container->get(AuthenticationService::class);
        $reCaptchaManager = $container->get(ReCaptchaManager::class);
        $mailManager = $container->get(MailManager::class);

        return new IndexController(
            $mailSender,
            $entityManager,
            $postManager,
            $authService,
            $reCaptchaManager,
            $mailManager
        );
    }
}