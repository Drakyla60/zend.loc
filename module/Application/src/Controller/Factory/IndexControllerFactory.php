<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Application\Service\MailSender;
use Application\Service\Admin\PostManager;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Application\Service\Admin\ImageManager;
use Application\Service\Admin\MailManager;
use Application\Service\Admin\ReCaptchaManager;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface|\Psr\Container\ContainerInterface $container, $requestedName, array $options = null): IndexController
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