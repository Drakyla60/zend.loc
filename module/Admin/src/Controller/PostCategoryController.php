<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Entity\PostCategory;
use Admin\Form\PostCategoryForm;

class PostCategoryController extends AbstractActionController
{
    private $entityManager;
    private $postCategoryManager;

    public function __construct($entityManager,$postCategoryManager)
    {
        $this->entityManager = $entityManager;
        $this->postCategoryManager = $postCategoryManager;
    }
    public function indexAction()
    {
        $categories = $this->entityManager->getRepository(PostCategory::class)->findAll();

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel(['categories' => $categories]);
    }

    public function addAction()
    {
        $form = new PostCategoryForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->postCategoryManager->addPostCategory($data);
                $this->logger('info', 'Додано нову Категорію : '. $data['post-category-name']);
                return $this->redirect()->toRoute('posts-category');
            }
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $postCategory = $this->entityManager->getRepository(PostCategory::class)
            ->find($id);

        if ($postCategory == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new PostCategoryForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->postCategoryManager->updatePostCategory($postCategory, $data);
                $this->flashMessenger()->addSuccessMessage('Updated the tag ' . $data['post-category-name']);
                return $this->redirect()->toRoute('posts-category', ['action' => 'index']);
            }
        } else {
            $form->setData([
                'post-category-name' => $postCategory->getCategoryName(),
                'post-category-description' => $postCategory->getCategoryDescription(),
            ]);
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form,
            'postCategory' => $postCategory
        ]);
    }

    public function viewAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        $postCategory = $this->entityManager
            ->getRepository(PostCategory::class)->find($id);

        if ($postCategory == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'postCategory' => $postCategory,
        ]);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        $category = $this->entityManager
            ->getRepository(PostCategory::class)->find($id);

        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->postCategoryManager->removePostCategory($category);
        return $this->redirect()->toRoute('posts-category', ['action' => 'index']);
    }

    public function restoreAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        $category = $this->entityManager
            ->getRepository(PostCategory::class)->find($id);

        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->postCategoryManager->restorePostCategory($category);
        return $this->redirect()->toRoute('posts-category', ['action' => 'index']);
    }
}