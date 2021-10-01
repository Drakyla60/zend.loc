<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Entity\Post;
use Application\Form\ContactForm;
use Doctrine\ORM\EntityManager;
use Laminas\Barcode\Barcode;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{

    /**
     * @var
     */
    protected $mailSender;
    /**
     * Менеджер сущностей.
     * @var EntityManager
     */
    private $entityManager;


    /**
     * @param $mailSender
     */
    public function __construct($mailSender, $entityManager)
    {
        $this->mailSender = $mailSender;
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        // Отримуємо останні пости.
        $posts = $this->entityManager->getRepository(Post::class)
            ->findBy(['status'=>Post::STATUS_PUBLISHED],
                ['dateCreated'=>'DESC']);

        return new ViewModel([
            'posts' => $posts
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

        if ($this->getRequest()->isPost()) {

            $formData = $this->params()->fromPost();
            $form->setData($formData);

            if ($form->isValid()) {

                $formData = $form->getData();

                if(!$this->mailSender
                    ->sendMail('Drakyla60@gmail.com', $formData['email'], $formData['subject'], $formData['body'])) {
                    return $this->redirect()->toRoute('application', ['action'=>'sendError']);
                }

                return $this->redirect()->toRoute('application', ['action' => 'thankYou']);
            } else {

                return new ViewModel([
                    'form' => $form,
                ]);
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return ViewModel
     */
    public function thankYouAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function sendErrorAction()
    {
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
