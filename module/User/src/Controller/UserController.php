<?php

declare(strict_types=1);

namespace User\Controller;

use Doctrine\ORM\EntityManager;
use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator;
use Laminas\ReCaptcha\ReCaptcha;
use Laminas\View\Model\ViewModel;
use User\Entity\Role;
use User\Entity\User;
use User\Form\EditUserForm;
use User\Form\PasswordChangeForm;
use User\Form\PasswordResetForm;
use User\Form\AddUserForm;
use User\Service\UserManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 *
 */
class UserController extends AbstractActionController
{
    private $entityManager;
    private $userManager;
    private $sessionContainer;
    private $reCaptchaManager;

    public function __construct($entityManager, $userManager, $reCaptchaManager, $sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->sessionContainer = $sessionContainer;
        $this->reCaptchaManager = $reCaptchaManager;
    }

    public function indexAction(): ViewModel
    {
        // Access control.
//        if (!$this->access('user.manage')) {
//            $this->getResponse()->setStatusCode(401);
//            return;
//        }
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager
            ->getRepository(User::class)
            ->findAllUsers();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(2);
        $paginator->setCurrentPageNumber($page);

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'users' => $paginator
        ]);
    }

    public function addAction()
    {
        $form = new AddUserForm($this->entityManager);
        // Get the list of all available roles (sorted by name).
        $allRoles = $this->entityManager->getRepository(Role::class)
            ->findBy([], ['name'=>'ASC']);
        $roleList = [];
        foreach ($allRoles as $role) {
            $roleList[$role->getId()] = $role->getName();
        }

        $form->get('roles')->setValueOptions($roleList);

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
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form
        ]);

    }

    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
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
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'user' => $user
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new EditUserForm($this->entityManager, $user);

        $allRoles = $this->entityManager->getRepository(Role::class)
            ->findBy([], ['name'=>'ASC']);
        $roleList = [];
        foreach ($allRoles as $role) {
            $roleList[$role->getId()] = $role->getName();
        }

        $form->get('roles')->setValueOptions($roleList);


        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
//            unset($data['roles']);
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $this->userManager->updateUser($user, $data);

                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        } else {

            $userRoleIds = [];
            foreach ($user->getRoles() as $role) {
                $userRoleIds[] = $role->getId();
            }

            $form->setData([
                'full_name' => $user->getFullName(),
                'email' => $user->getEmail(),
                'status' => $user->getStatus(),
                'roles' => $userRoleIds
            ]);
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
    }

    public function emailConfirmationAction()
    {

        $email = $this->params()->fromQuery('email', null);
        $token = $this->params()->fromQuery('token', null);

        // Validate token length
        if ($token != null && (!is_string($token) || strlen($token) != 32)) {
            throw new Exception('Invalid token type or length');
        }
        try {
            $this->userManager->activateUser($email, $token);
            return $this->redirect()->toRoute('login');
        } catch (Exception $exception) {
            $this->flashMessenger()
                ->addErrorMessage('Виникла помилка : ' . $exception->getMessage());
            return $this->redirect()->toRoute('home');
        }


    }

    public function changePasswordAction()
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

        // Create "change password" form
        $form = new PasswordChangeForm('change');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Try to change password.
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage(
                        'Sorry, the old password is incorrect. Could not set the new password.');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Changed the password successfully.');
                }

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action'=>'view', 'id'=>$user->getId()]);
            }
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    public function resetPasswordAction()
    {
        $form = new PasswordResetForm();

        $recaptcha = $this->reCaptchaManager->init();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            $result = $this->reCaptchaManager->checkReCaptcha($data['g-recaptcha-response']);

            if ($form->isValid() && true == $result) {
                $user = $this
                    ->entityManager
                    ->getRepository(User::class)
                    ->findOneByEmail($data['email']);

                if ($user != null && $user->getStatus() == User::STATUS_ACTIVE) {
                    $this->userManager->generatePasswordResetToken($user);
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'sent']);
                } else {
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'invalid-email']);
                }
            }
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form,
            'recaptcha' => $recaptcha,
        ]);
    }

    public function setPasswordAction()
    {
        $email = $this->params()->fromQuery('email', null);
        $token = $this->params()->fromQuery('token', null);

        // Validate token length
        if ($token != null && (!is_string($token) || strlen($token) != 32)) {
            throw new Exception('Invalid token type or length');
        }

        if ($token === null ||
            !$this->userManager->validatePasswordResetToken($email, $token)) {
            return $this->redirect()->toRoute('users',
                ['action' => 'message', 'id' => 'failed']);
        }

        // Create form
        $form = new PasswordChangeForm('reset');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                $data = $form->getData();

                // Set new password for the user.
                if ($this->userManager->setNewPasswordByToken($email, $token, $data['new_password'])) {

                    // Redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'set']);
                } else {
                    // Redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'failed']);
                }
            }
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function messageAction(): ViewModel
    {
        // Get message ID from route.
        $id = (string)$this->params()->fromRoute('id');

        // Validate input argument.
        if ($id != 'invalid-email' && $id != 'sent' && $id != 'set' && $id != 'failed') {
            throw new Exception('Invalid message ID specified');
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'id' => $id
        ]);
    }

}
