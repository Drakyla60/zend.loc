<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Entity\Post;
use Application\Form\ContactForm;
use Application\Service\ThumbsManager;
use Laminas\Barcode\Barcode;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{
    private $entityManager;
//    private $postManager;
    private $authService;
    private $reCaptchaManager;
    private $mailManager;
    private $imageManager;

    public function __construct($entityManager,
//                                $postManager,
                                $authService,
                                $reCaptchaManager,
                                $mailManager,
                                $imageManager
    )
    {
        $this->entityManager    = $entityManager;
//        $this->postManager      = $postManager;
        $this->authService      = $authService;
        $this->reCaptchaManager = $reCaptchaManager;
        $this->mailManager      = $mailManager;
        $this->imageManager     = $imageManager;
    }

    public function indexAction()
    {
        $name = $this->authService->getIdentity();

        $this->layout()->setTemplate('layout/application_layout');
        return new ViewModel([
//            'posts'       => $paginator,
            'loginName' => $name,
        ]);
    }

    /**
     * @return ViewModel
     */
    public function aboutAction()
    {
        $meta = [
            'title' => 'About page'
        ];
        $appName = 'Hello World';

        $this->layout()->setTemplate('layout/application_layout');
        return new ViewModel([
            'meta' => $meta,
            'appName' => $appName,
        ]);
    }

    public function contactUsAction()
    {
        $form = new ContactForm();
        $recaptcha = $this->reCaptchaManager->init();

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);
            $result = $this->reCaptchaManager->checkReCaptcha($data['g-recaptcha-response']);
            if ($form->isValid() && true == $result) {

                $formData = $form->getData();

                $formData = $this->imageManager->uploadContactUsImage($formData);

                $option = [
                    'subjectEmail' => 'Користувач :' . $formData['email'] . ' Написав вам лист через контактну форму',
                    'bodyHtml'     => 'Тема листа : ' . $formData['subject'] . '<br>' .
                                      'Текст листа :' . $formData['body'],
                    'file'         => $formData['file'],
                ];
//                die();
                if(!$this->mailManager->sendMailWithContactUs($formData, $option)) {
                    return $this->redirect()->toRoute('application', ['action'=>'sendError']);
                }

                return $this->redirect()->toRoute('application', ['action' => 'thankYou']);
            } else {
                $this->layout()->setTemplate('layout/application_layout');
                return new ViewModel([
                    'form' => $form,
                    'recaptcha' => $recaptcha
                ]);
            }
        }
        $this->layout()->setTemplate('layout/application_layout');
        return new ViewModel([
            'form' => $form,
            'recaptcha' => $recaptcha
        ]);
    }

    /**
     * @return ViewModel
     */
    public function thankYouAction()
    {
        $this->layout()->setTemplate('layout/application_layout');
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function sendErrorAction()
    {
        $this->layout()->setTemplate('layout/application_layout');
        return new ViewModel();
    }

    /**
     * @return \Laminas\Http\PhpEnvironment\Response|\Laminas\Stdlib\ResponseInterface
     */
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
