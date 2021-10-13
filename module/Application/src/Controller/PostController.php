<?php

namespace Application\Controller;

use Application\Form\PostForm;
use Application\Service\PostManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PostController extends AbstractActionController
{

    private EntityManager $entityManager;
    private PostManager $postManager;

    public function __construct($entityManager, $postManager)
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function addAction()
    {
        $form = new PostForm();

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {

                $data = $form->getData();

                $this->postManager->addNewPost($data);

                return $this->redirect()->toRoute('application');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }
}