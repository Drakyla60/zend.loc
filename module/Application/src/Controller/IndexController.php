<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Form\ContactForm;
use Laminas\Barcode\Barcode;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $mailSender;

    public function __construct($mailSender)
    {
        $this->mailSender = $mailSender;
    }

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
                $email = $data['email'];
                $subject = $data['subject'];
                $body = $data['body'];

                if(!$this->mailSender->sendMail('Drakyla60@gmail.com', $email, $subject, $body)) {
                    return $this->redirect()->toRoute('application', ['action'=>'sendError']);
                }

                return $this->redirect()->toRoute('application', ['action' => 'thankYou']);
            } else {
                echo 'Error';
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function thankYouAction()
    {
        return 'thankYou';
//        return new ViewModel();
    }

    public function sendErrorAction()
    {
        return 'sendError';
//        return new ViewModel();
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

}
