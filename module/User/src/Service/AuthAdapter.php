<?php

namespace User\Service;

use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use User\Entity\User;

class AuthAdapter
{

    private $email;

    private $password;

    private $entityManager;


    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Задает эл. адрес пользователя.
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Устанавливает пароль.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    public function authenticate()
    {
        // Проверяем, есть ли в базе данных пользователь с таким адресом.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($this->email);

        // Если такого пользователя нет, возвращаем статус 'Identity Not Found'.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        // Если пользователь с таким адресом существует, необходимо проверить, активен ли он.
        // Неактивные пользователи не могут входить в систему.
        if ($user->getStatus()==User::STATUS_RETIRED) {
            return new Result(
                Result::FAILURE,
                null,
                ['User is retired.']);
        }

        // Теперь необходимо вычислить хэш на основе введенного пользователем пароля и сравнить его
        // с хэшем пароля из базы данных.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Отлично! Хэши паролей совпадают. Возвращаем личность пользователя (эл. адрес) для
            // хранения в сессии с целью последующего использования.
            return new Result(
                Result::SUCCESS,
                $this->email,
                ['Authenticated successfully.']);
        }

        // Если пароль не прошел проверку, возвращаем статус ошибки 'Invalid Credential'.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}