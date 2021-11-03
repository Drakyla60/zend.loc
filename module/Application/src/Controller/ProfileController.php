<?php


namespace Application\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class ProfileController extends AbstractActionController
{
    public function indexAction()
    {
        $meta = [
            'title' => 'My Controller page!'
        ];

        return new ViewModel([
            'meta' => $meta,
        ]);
    }

    public function getJsonAction()
    {
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Here is your data',
            'data' => [
                'full_name' => 'John Doe',
                'address' => '51 Middle st.'
            ]
        ]);
    }


}