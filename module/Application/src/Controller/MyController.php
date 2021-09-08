<?php


namespace Application\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class MyController extends AbstractActionController
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

    // Пример действия.
    public function someAction()
    {
        // Получаем один параметр 'id' от маршрута.
        $id = $this->params()->fromRoute('id', -1);

        // Получаем все параметры маршрута сразу в виде массива.
        $params = $this->params()->fromRoute();

        //...
    }

    // An example action.
    public function someoneAction()
    {
        // Получает объект RouteMatch.
        $routeMatch = $this->getEvent()->getRouteMatch();

        // Получает имя соответствующего маршрута.
        $routeName = $routeMatch->getMatchedRouteName();

        // Получает все параметры маршрута в виде массива.
        $params = $routeMatch->getParams();

        //...
    }

}