<?php

namespace Application\Controller;

use Application\Entity\Post;
use Application\Form\CommentForm;
use Application\Form\PostForm;
use Application\Service\PostManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Laminas\Http\Response;
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

    public function viewAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this
            ->entityManager
            ->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $commentCount = $this->postManager->getCommentCountStr($post);
        $form = new CommentForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->postManager->addCommentToPost($post, $data);

                return $this->redirect()->toRoute('posts',
                    [
                        'action' => 'view',
                        'id'     => $postId
                    ]);
            }
        }

        return new ViewModel([
            'post'         => $post,
            'commentCount' => $commentCount,
            'form'         => $form,
            'postManager'  => $this->postManager
        ]);
    }

    /**
     * @return Response|ViewModel|void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function editAction()
    {
        $form = new PostForm();
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this
            ->entityManager
            ->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->postManager->updatePost($post, $data);

                return $this
                    ->redirect()
                    ->toRoute('posts', [
                        'action' => 'admin'
                    ]);
            }
        } else {
            $data = [
                'title'   => $post->getTitle(),
                'content' => $post->getContent(),
                'tags'    => $this->postManager->convertTagsToString($post),
                'status'  => $post->getStatus()
            ];

            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'post' => $post
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function deleteAction()
    {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this
            ->entityManager
            ->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->postManager->removePost($post);

        return $this->redirect()->toRoute('posts', ['action' => 'admin']);
    }

    public function adminAction()
    {
        $posts = $this->entityManager->getRepository(Post::class)
            ->findBy([], ['dateCreated'=>'DESC']);

        return new ViewModel([
            'posts'       => $posts,
            'postManager' => $this->postManager
        ]);
    }
}