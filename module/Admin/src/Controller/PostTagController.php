<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Entity\Tag;
use Admin\Form\PostTagForm;

class PostTagController extends AbstractActionController
{
    private $entityManager;
    private $tagManager;

    public function __construct($entityManager, $tagManager)
    {
        $this->entityManager = $entityManager;
        $this->tagManager = $tagManager;
    }
    public function indexAction()
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel(['tags' => $tags]);
    }

    public function addAction()
    {

        $form = new PostTagForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->tagManager->addTag($data);
                $this->logger('info', 'Додано нову Категорію : '. $data['post-category-name']);
                return $this->redirect()->toRoute('posts-tag');
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

        $tag = $this->entityManager->getRepository(Tag::class)
            ->find($id);

        if ($tag == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new PostTagForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->tagManager->updateTag($tag, $data);
                $this->flashMessenger()->addSuccessMessage('Updated the tag ' . $data['post-tag-name']);
                return $this->redirect()->toRoute('posts-tags', ['action' => 'index']);
            }
        } else {
            $form->setData([
                'post-tag-name' => $tag->getName(),
            ]);
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form,
            'tag' => $tag
        ]);
    }

    public function viewAction()
    {
        $tagId = $this->params()->fromRoute('id', -1);

        $tag = $this->entityManager
            ->getRepository(Tag::class)->findOneById($tagId);

        if ($tag == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'tag' => $tag,
        ]);
    }

    public function deleteAction()
    {
        $tagId = $this->params()->fromRoute('id', -1);

        $tag = $this->entityManager
            ->getRepository(Tag::class)->findOneById($tagId);

        if ($tag == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->tagManager->removeTag($tag);
        return $this->redirect()->toRoute('posts-tags', ['action' => 'index']);
    }

    public function restoreAction()
    {
        $tagId = $this->params()->fromRoute('id', -1);

        $tag = $this->entityManager
            ->getRepository(Tag::class)->findOneById($tagId);

        if ($tag == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->tagManager->restoreTag($tag);
        return $this->redirect()->toRoute('posts-tags', ['action' => 'index']);
    }
}