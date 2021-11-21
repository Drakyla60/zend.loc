<?php

namespace User\Controller;

use User\Entity\Post;
use User\Entity\User;
use User\Form\CommentForm;
use User\Form\PostForm;
use User\Service\PostManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;
use User\Service\UserManager;

class PostController extends AbstractActionController
{

    private EntityManager $entityManager;
    private PostManager $postManager;
    private UserManager $userManager;
    private $imageManager;

    public function __construct($entityManager, $postManager, $userManager, $imageManager)
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
        $this->userManager = $userManager;
        $this->imageManager = $imageManager;
    }

    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);
//        $tagFilter = $this->params()->fromQuery('tag', null);

//        if ($tagFilter) {
//            $query = $this->entityManager
//                ->getRepository(Post::class)->findPostsByTag($tagFilter);
//        } else {
        $query = $this->entityManager
            ->getRepository(Post::class)->findAllPosts();
//        }

        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);

        $paginator->setDefaultItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        $tagCloud = $this->postManager->getTagCloud();

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'posts'       => $paginator,
            'postManager' => $this->postManager,
            'tagCloud'    => $tagCloud,
        ]);
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
                $this->logger('info', 'Додано новий Пост: '. $data['title']);
                return $this->redirect()->toRoute('posts');
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
                $this->logger('info', 'Додано новий Коментар: '. $data['author'] . ' - '. $data['comment']);
                return $this->redirect()->toRoute('posts',
                    [
                        'action' => 'view',
                        'id'     => $postId
                    ]);
            }
        }
        $this->logger('info', 'Переглянуто пост: '. $post->getTitle());
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

                return $this->redirect()->toRoute('posts');
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

}