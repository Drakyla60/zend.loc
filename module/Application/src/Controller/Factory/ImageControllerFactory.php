<?php

namespace Application\Controller\Factory;

use Application\Controller\ImageController;
use Application\Service\ImageManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ImageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ImageController
    {
        $imageManager = $container->get(ImageManager::class);
        return new ImageController($imageManager);
    }
}