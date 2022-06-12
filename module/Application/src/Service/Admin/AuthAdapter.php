<?php

namespace Application\Service\Admin;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Application\Entity\User;

class AuthAdapter implements AdapterInterface
{
    private $email;
    private $password;
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function authenticate()
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($this->email);

        if ($user == null) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid credentials.']);
        }

        // Якщо користувач із такою адресою існує, необхідно перевірити, чи активний він.
        // Неактивні користувачі не можуть входити в систему.
        if ($user->getStatus()==User::STATUS_RETIRED) {
            return new Result(Result::FAILURE, null, ['User is retired.']);
        }

        // Теперь необходимо вычислить хэш на основе введенного пользователем пароля и сравнить его
        // с хэшем пароля из базы данных.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Хеши паролів збігаються. Повертаємо особистість користувача (ел. Адреса) для
            // зберігання в сесії з метою подальшого використання.
            return new Result(Result::SUCCESS, $this->email, ['Authenticated successfully.']);
        }

        // Якщо пароль не пройшов перевірку, повертаємо статус помилки 'Invalid Credential'.
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid credentials.']);
    }
}