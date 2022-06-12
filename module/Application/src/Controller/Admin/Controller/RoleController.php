<?php

namespace Application\Controller\Admin\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Entity\Permission;
use Application\Entity\Role;
use Application\Form\Admin\RoleForm;
use Application\Form\Admin\RolePermissionsForm;
use Application\Service\ADMIN\RoleManager;

class RoleController extends AbstractActionController
{
    private EntityManager $entityManager;
    private RoleManager $roleManager;

    public function __construct($entityManager, $roleManager)
    {
        $this->entityManager = $entityManager;
        $this->roleManager = $roleManager;
    }

    public function indexAction(): ViewModel
    {
        $roles = $this->entityManager
            ->getRepository(Role::class)
            ->findBy([], ['id' => 'ASC']);
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'roles' => $roles,
        ]);
    }

    public function addAction()
    {
        $form = new RoleForm('create', $this->entityManager);

        $roleList = [];
        $roles = $this->entityManager
            ->getRepository(Role::class)
            ->findBy([], ['name' => 'ASC']);
        foreach ($roles as $role) {
            $roleList[$role->getId()] = $role->getName();
        }
        $form->get('inherit_roles')->setValueOptions($roleList);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Add role.
                $this->roleManager->addRole($data);

                // Add a flash message.
//                $this->flashMessenger()->addSuccessMessage('Added new role.');

                // Redirect to "index" page
                return $this->redirect()->toRoute('roles', ['action' => 'index']);
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

        // Find a role with such ID.
        $role = $this->entityManager->getRepository(Role::class)
            ->find($id);

        if ($role == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $allPermissions = $this->entityManager->getRepository(Permission::class)
            ->findBy([], ['name' => 'ASC']);

        $effectivePermissions = $this->roleManager->getEffectivePermissions($role);
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'role' => $role,
            'allPermissions' => $allPermissions,
            'effectivePermissions' => $effectivePermissions
        ]);
    }

    /**
     * This action displays a page allowing to edit an existing role.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $role = $this->entityManager->getRepository(Role::class)
            ->find($id);

        if ($role == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        // Create form
        $form = new RoleForm('update', $this->entityManager, $role);

        $roleList = [];
        $selectedRoles = [];
        $roles = $this->entityManager->getRepository(Role::class)
            ->findBy([], ['name' => 'ASC']);
        foreach ($roles as $role2) {

            if ($role2->getId() == $role->getId())
                continue; // Do not inherit from ourselves

            $roleList[$role2->getId()] = $role2->getName();

            if ($role->hasParent($role2))
                $selectedRoles[] = $role2->getId();
        }
        $form->get('inherit_roles')->setValueOptions($roleList);

        $form->get('inherit_roles')->setValue($selectedRoles);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Update permission.
                $this->roleManager->updateRole($role, $data);

                // Add a flash message.
//                $this->flashMessenger()->addSuccessMessage('Updated the role.');

                // Redirect to "index" page
                return $this->redirect()->toRoute('roles', ['action' => 'index']);
            }
        } else {
            $form->setData(array(
                'name' => $role->getName(),
                'description' => $role->getDescription()
            ));
        }
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form,
            'role' => $role
        ]);
    }

    /**
     * The "editPermissions" action allows to edit permissions assigned to the given role.
     */
    public function editPermissionsAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $role = $this->entityManager->getRepository(Role::class)
            ->find($id);

        if ($role == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $allPermissions = $this->entityManager->getRepository(Permission::class)
            ->findBy([], ['name' => 'ASC']);

        $effectivePermissions = $this->roleManager->getEffectivePermissions($role);

        // Create form
        $form = new RolePermissionsForm($this->entityManager);
        foreach ($allPermissions as $permission) {
            $label = $permission->getName();
            $isDisabled = false;
            if (isset($effectivePermissions[$permission->getName()]) && $effectivePermissions[$permission->getName()] == 'inherited') {
                $label .= ' (inherited)';
                $isDisabled = true;
            }
            $form->addPermissionField($permission->getName(), $label, $isDisabled);
        }

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Update permissions.
                $this->roleManager->updateRolePermissions($role, $data);

                // Add a flash message.
//                $this->flashMessenger()->addSuccessMessage('Updated permissions for the role.');

                // Redirect to "index" page
                return $this->redirect()->toRoute('roles', ['action' => 'view', 'id' => $role->getId()]);
            }
        } else {

            $data = [];
            foreach ($effectivePermissions as $name => $inherited) {
                $data['permissions'][$name] = 1;
            }

            $form->setData($data);
        }

        $errors = $form->getMessages();
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([
            'form' => $form,
            'role' => $role,
            'allPermissions' => $allPermissions,
            'effectivePermissions' => $effectivePermissions
        ]);
    }

    /**
     * This action deletes a permission.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $role = $this->entityManager->getRepository(Role::class)
            ->find($id);

        if ($role == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        // Delete role.
        $this->roleManager->deleteRole($role);

        // Add a flash message.
//        $this->flashMessenger()->addSuccessMessage('Deleted the role.');

        // Redirect to "index" page
        return $this->redirect()->toRoute('roles', ['action' => 'index']);
    }
}