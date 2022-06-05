<?php

declare(strict_types=1);

namespace Application\Controller;

use Admin\Entity\Post;
use Admin\Entity\PostCategory;
use Application\Form\ContactForm;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Laminas\Barcode\Barcode;
use Laminas\Json\Json;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Predis\Autoloader;
use Predis\Client;

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

        $page = $this->params()->fromQuery('page', 1);

//        $query = $this->entityManager
//            ->getRepository(Post::class)->findAllPosts();
        $query2 = $this->entityManager
            ->getRepository(PostCategory::class)->findAll();


        $data = [];
        foreach ($query2 as $item ) {

//            var_dump($item);
            $data[] = [
                'category_id' => $item->getCAtegoryId(),
                'category_name' => $item->getCategoryName(),
                'category_description' => $item->getCategoryDescription(),
                'category_parent_id' => $item->getCategoryParentId(),
            ];
//            var_dump($data);
        }
//        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
//        $paginator = new Paginator($doctrinePaginator);
//
//        $paginator->setDefaultItemCountPerPage(5);
//        $paginator->setCurrentPageNumber($page);

//        $tagCloud = $this->postManager->getTagCloud();
//
//        foreach ($paginator as $item) {
//            $item->setStatus($this->postManager->getPostStatusAsString($item->getStatus()));
//        }

////        $this->layout()->setTemplate('layout/users_layout');
//        return new ViewModel([
////            'posts'       => $paginator,
////            'tagCloud'    => $tagCloud,
//        ]);

//        $this->layout()->setTemplate('layout/application_layout');
//        return new ViewModel([
////            'posts'       => $paginator,
//            'loginName' => $name,
//        ]);
        //TODO Винести треба то звідси
        header('Access-Control-Allow-Origin: *');

        $client = new Client('tcp://redis:6379');

        $jsonData = new JsonModel($data);
        $jsonData = serialize($jsonData);

        if ($client->get('API_INDEX')) {
            $jsonData = unserialize($client->get('API_INDEX'));
        } else {
            $client->set('API_INDEX', $jsonData);
        }

        return $jsonData;
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
