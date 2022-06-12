<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Authentication\AuthenticationService;
use Laminas\Permissions\Rbac\Rbac;
use Application\Entity\User;

class RbacAssertionManager
{
    private EntityManager $entityManager;
    private AuthenticationService $authService;

    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    public function assert(Rbac $rbac, $permission, $params): bool
    {
        $currentUser = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneByEmail($this->authService->getIdentity());

        if ($permission == 'profile.own.view' && $params['user']->getId() == $currentUser->getId()) {
            return true;
        }
        return false;
    }
}