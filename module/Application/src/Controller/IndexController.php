<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Entity\Post;
use Application\Form\ContactForm;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Laminas\Barcode\Barcode;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{

    private $mailSender;
    private $entityManager;
    private $postManager;
    private $authService;
    private $reCaptchaManager;
    private $mailManager;

    public function __construct($mailSender,
                                $entityManager,
                                $postManager,
                                $authService,
                                $reCaptchaManager,
                                $mailManager
    )
    {
        $this->mailSender = $mailSender;
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
        $this->authService = $authService;
        $this->reCaptchaManager = $reCaptchaManager;
        $this->mailManager = $mailManager;
    }

    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        $tagFilter = $this->params()->fromQuery('tag', null);

        $name = $this->authService->getIdentity();

        if ($tagFilter) {

            $query = $this
                ->entityManager
                ->getRepository(Post::class)
                ->findPostsByTag($tagFilter);

        } else {
            $query = $this
                ->entityManager
                ->getRepository(Post::class)
                ->findPublishedPosts();
        }

        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);

        $paginator->setDefaultItemCountPerPage(2);
        $paginator->setCurrentPageNumber($page);

        $tagCloud = $this->postManager->getTagCloud();

        $this->layout()->setTemplate('layout/application_layout');
        return new ViewModel([
            'posts'       => $paginator,
            'postManager' => $this->postManager,
            'tagCloud'    => $tagCloud,
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

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
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
                $var = $formData['file'];
                if (null != $formData['file']) {
                    $path = $formData['file']['tmp_name'];
                    $savePath = './data/contact-us' . '/'. time() .'_'. $formData['file']['name'];

                    $result = move_uploaded_file($path, $savePath);
                }

                $option = [
                    'subjectEmail' => 'Користувач : <b>' . $formData['email'] . '</b> Написав вам лист через контактну форму',
                    'bodyHtml'     => 'Тема листа : ' . $formData['subject'] . '<br>' .
                                      'Текст листа :' . $formData['body'],
                    'file'         => $savePath,
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
