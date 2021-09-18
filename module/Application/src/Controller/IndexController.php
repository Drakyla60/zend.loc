<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Form\ContactForm;
use Laminas\Barcode\Barcode;
use Laminas\Mvc\Controller\AbstractActionController;
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

    public function contactUsAction()
    {
        $form = new ContactForm();

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                var_dump($data);
                //TODO Save data

                return $this->redirect()->toRoute('application', ['action' => 'thankYou']);
            } else {
                echo 'Error';
            }
        }

        return new ViewModel(
            [
                'form' => $form
            ]
        );
    }

    public function barcodeAction()
    {
        $type = $this->params()->fromRoute('type', 'code39');
        $label = $this->params()->fromRoute('label', 'HELLO-WORLD');

        $barcodeOptions = ['text' => $label];
        $rendererOptions = [];

        $barcode = Barcode::factory($type, 'image',
            $barcodeOptions, $rendererOptions);

        $barcode->render();

        return $this->getResponse();
    }

    public function docAction()
    {
        $pageTemplate = 'application/index/doc' .
            $this->params()->fromRoute('page', 'documentation.phtml');

        $filePath = __DIR__ . '/../../view/' . $pageTemplate . '.phtml';
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $viewModel = new ViewModel([
            'page' => $pageTemplate
        ]);
        $viewModel->setTemplate($pageTemplate);

        return $viewModel;
    }

    public function staticAction()
    {
        $pageTemplate = $this->params()->fromRoute('page', null);
        if ($pageTemplate == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $viewModel = new ViewModel([
            'page' => $pageTemplate
        ]);
        $viewModel->setTemplate($pageTemplate);
        return $viewModel;
    }
}
