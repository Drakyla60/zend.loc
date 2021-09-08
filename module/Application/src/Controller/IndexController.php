<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Barcode\Barcode;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function aboutAction()
    {
        $meta = [
            'title' => 'About page'
        ];
        $appName = 'Hello World';

        return new ViewModel([
            'meta' => $meta,
            'appName' => $appName,
        ]);
    }

    // Действие "barcode"
    public function barcodeAction()
    {
        // Получаем параметры от маршрута.
        $type = $this->params()->fromRoute('type', 'code39');
        $label = $this->params()->fromRoute('label', 'HELLO-WORLD');

        // Устанавливаем опции штрих-кода.
        $barcodeOptions = ['text' => $label];
        $rendererOptions = [];

        // Создаем объект штрих-кода.
        $barcode = Barcode::factory($type, 'image',
            $barcodeOptions, $rendererOptions);

        // Строка ниже выведет изображение штрих-кода в
        // стандартный поток вывода.
        $barcode->render();

        // Возвращаем объект Response, чтобы отключить визуализацию стандартного представления.
        return $this->getResponse();
    }

    public function docAction()
    {
        $pageTemplate = 'application/index/doc'.
            $this->params()->fromRoute('page', 'documentation.phtml');

        $filePath = __DIR__.'/../../view/'.$pageTemplate.'.phtml';
        if(!file_exists($filePath) || !is_readable($filePath)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $viewModel = new ViewModel([
            'page'=>$pageTemplate
        ]);
        $viewModel->setTemplate($pageTemplate);

        return $viewModel;
    }

    public function staticAction()
    {
        // Получаем путь к шаблону представления от параметров маршрута
        $pageTemplate = $this->params()->fromRoute('page', null);
        if($pageTemplate==null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Визуализируем страницу
        $viewModel = new ViewModel([
            'page'=>$pageTemplate
        ]);
        $viewModel->setTemplate($pageTemplate);
        return $viewModel;
    }
}
