<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Application\Service\MailSender;
use Application\Service\PostManager;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Admin\Service\ImageManager;
use Admin\Service\MailManager;
use Admin\Service\ReCaptchaManager;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IndexController
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
//        $postManager = $container->get(PostManager::class);
        $authService = $container->get(AuthenticationService::class);
        $reCaptchaManager = $container->get(ReCaptchaManager::class);
        $mailManager = $container->get(MailManager::class);
        $imageManager = $container->get(ImageManager::class);

        return new IndexController(
            $entityManager,
//            $postManager,
            $authService,
            $reCaptchaManager,
            $mailManager,
            $imageManager
        );
    }
}