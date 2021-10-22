<?php

namespace User\Service;

use Doctrine\ORM\EntityManager;
use Exception;
use Laminas\Crypt\Password\Bcrypt;
use User\Entity\User;

class UserManager
{

    private EntityManager $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function addUser($data): User
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);
        $user->setPassword($passwordHash);
        $user->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * This method updates data of an existing user.
     * @throws Exception
     */
    public function updateUser(User $user, $data): User
    {
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);
        $user->setStatus($data['status']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}