<?php

namespace Application\Service\Admin;

use Laminas\Cache\Exception\ExceptionInterface;
use Laminas\Permissions\Rbac\Rbac;
use Application\Entity\Role;
use Application\Entity\User;

class RbacManager
{
    private $entityManager;
    private $authService;
    private $rbac;
    private $cache;
    private array $assertionManagers = [];

    public function __construct($entityManager, $authService, $cache, $assertionManagers)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
        $this->cache = $cache;
        $this->assertionManagers = $assertionManagers;
    }

    /**
     * @throws ExceptionInterface
     */
    public function init($forceCreated = false)
    {
        if ($this->rbac != null && !$forceCreated) {
            return;
        }
        if ($forceCreated) {
            $this->cache->removeItem('rbacContainer');
        }

        $this->rbac = $this->cache->getItem('rbacContainer');

        if (!$this->rbac->isHit()) {
            $rbac = new Rbac();
            $this->rbac = $rbac;

            // Конструюємо ієрархію ролей, завантажуючи ролі та привілеї з бази даних.
            $rbac->setCreateMissingRoles(true);

            $roles = $this->entityManager
                ->getRepository(Role::class)
                ->findBy([], ['id'=>'ASC']);
            foreach ($roles as $role) {

                $roleName = $role->getName();

                $parentRoleNames = [];
                foreach ($role->getParentRoles() as $parentRole) {
                    $parentRoleNames[] = $parentRole->getName();
                }

                $rbac->addRole($roleName, $parentRoleNames);

                foreach ($role->getPermissions() as $permission) {
                    $rbac->getRole($roleName)->addPermission($permission->getName());
                }
            }

            // Сохраняем контейнер Rbac в кэш.
            $demoString = $this->cache->getItem('rbac_container');
            if (!$demoString->isHit())
            {
                $demoString->set($rbac);
                $this->cache->save($demoString);
            }
//            $this->cache->save('rbac_container', $rbac);
        }
    }

    /**
     * Перевіряє, чи є привілей цього користувача.
     * @param User|null $user
     * @param string $permission
     * @param array|null $params
     * @return bool
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function isGranted(?User $user, string $permission, array $params = null): bool
    {
        if ($this->rbac==null) {
            $this->init();
        }

        if ($user==null) {

            $identity = $this->authService->getIdentity();
            if ($identity==null) {
                return false;
            }

            $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($identity);
            if ($user==null) {
                // Ця особа є у сесії, але у базі даних такого користувача немає.
                // Ми генеруємо виняток, оскільки, можливо, це проблема безпеки.
                throw new \Exception('There is no user with such identity');
            }
        }

        $roles = $user->getRoles();

        foreach ($roles as $role) {
            if ($this->rbac->isGranted($role->getName(), $permission)) {

                if ($params==null)
                    return true;

                foreach ($this->assertionManagers as $assertionManager) {
                    if ($assertionManager->assert($this->rbac, $permission, $params))
                        return true;
                }
            }

            $parentRoles = $role->getParentRoles();
            foreach ($parentRoles as $parentRole) {
                if ($this->rbac->isGranted($parentRole->getName(), $permission)) {
                    return true;
                }
            }
        }

        return false;
    }
}