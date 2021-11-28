<?php

declare(strict_types=1);

namespace Admin\Controller;


use Admin\Entity\Product;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{
    private $mongoManager;
    private $sessionContainer;

    public function __construct($sessionContainer, $mongoManager)
    {
        $this->mongoManager = $mongoManager;
        $this->sessionContainer = $sessionContainer;
    }
    public function indexAction(): ViewModel
    {

        $product = new Product();
        $product->setName('mangogo');
        $product->setOrigin('dspfjodks');
        $product->setPrice(50);

        $this->mongoManager->persist($product);

        $this->mongoManager->flush();

        $user = $this->mongoManager
            ->getRepository(Product::class)->findAll();



        return new ViewModel([]);
    }


}
