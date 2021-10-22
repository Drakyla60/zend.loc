<?php

declare(strict_types=1);

namespace User\Controller;


use Doctrine\ORM\EntityManager;
use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Entity\User;
use User\Form\UserForm;
use User\Service\UserManager;

/**
 *
 */
class UserController extends AbstractActionController
{
    private EntityManager $entityManager;
    private UserManager $userManager;

    public function __construct($entityManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function indexAction(): ViewModel
    {
        $query = $this
            ->entityManager
            ->getRepository(User::class)
            ->findAll();
//            ->findAllUsers();

        return new ViewModel([
            'users' => $query
        ]);
    }

    public function addAction()
    {
        $form = new UserForm('create');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {

                $data = $form->getData();
                $user = $this->userManager->addUser($data);

                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        }
        return new ViewModel([
            'form' => $form
        ]);

    }

    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        return new ViewModel([
            'user' => $user
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new UserForm('update', $user);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()) {
                $data = $form->getData();
                $this->userManager->updateUser($user, $data);

                return $this->redirect()->toRoute('users',
                    ['action'=>'view', 'id'=>$user->getId()]);
            }
        } else {

            $form->setData([
                'full_name'=>$user->getFullName(),
                'email'=>$user->getEmail(),
                'status'=>$user->getStatus(),
            ]);
        }

        return new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
    }

}
