<?php

namespace User\Service;

use Exception;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Math\Rand;
use PHPMailer\PHPMailer\PHPMailer;
use User\Entity\Role;
use User\Entity\User;

class UserManager
{

    private $entityManager;
    private $roleManager;
    private $mailManager;
    private $permissionManager;
    private $viewRenderer;
    private $config;

    public function __construct($entityManager, $roleManager, $mailManager, $permissionManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->roleManager = $roleManager;
        $this->mailManager = $mailManager;
        $this->permissionManager = $permissionManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    public function addUser($data): User
    {
        // Do not allow several users with the same email address.
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);
        $user->setPassword($passwordHash);
        $user->setStatus($data['status']);
        $user->setAvatar($data['avatar']);
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);
        $user->setDateUpdated($currentDate);

        $this->assignRoles($user, $data['roles']);


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function registrationUser($data): User
    {
        // Do not allow several users with the same email address.
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);
        $user->setPassword($passwordHash);
        $user->setStatus(User::STATUS_RETIRED);
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

        $this->assignRoles($user, [User::DEFAULT_ROLE]);


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
    /**
     * @throws Exception
     */
    public function updateUser(User $user, $data): User
    {

        // Do not allow to change user email if another user with such email already exits.
        if($user->getEmail()!=$data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }

        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);
        $user->setStatus($data['status']);

        $user->setAvatar($data['avatar']);
//        $currentDate = date('Y-m-d H:i:s');
//        $user->setDateUpdated($currentDate);
        $this->assignRoles($user, $data['roles']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * A helper method which assigns new roles to the user.
     */
    private function assignRoles($user, $roleIds)
    {
        // Remove old user role(s).
        $user->getRoles()->clear();

        // Assign new role(s).
        foreach ($roleIds as $roleId) {
            $role = $this->entityManager
                ->getRepository(Role::class)
                ->find($roleId);
            if ($role==null) {
                throw new \Exception('Not found role by ID');
            }

            $user->addRole($role);
        }
    }

    public function validatePassword($user, $password): bool
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }

    /**
     * This method checks if at least one user presents, and if not, creates
     * 'Admin' user with email 'admin@example.com' and password 'Secur1ty'.
     */
    public function createAdminUserIfNotExists()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user==null) {

            $this->permissionManager->createDefaultPermissionsIfNotExist();
            $this->roleManager->createDefaultRolesIfNotExist();

            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('Secur1ty');
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));

            // Assign user Administrator role
            $adminRole = $this->entityManager
                ->getRepository(Role::class)
                ->findOneByName('Administrator');
            if ($adminRole==null) {
                throw new \Exception('Administrator role doesn\'t exist');
            }

            $user->getRoles()->add($adminRole);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function createEmailConfirmationToken($user)
    {
        if ($user->getStatus() == User::STATUS_ACTIVE) {
            throw new Exception('Користувач ' . $user->getEmail() . 'вже підтвердив скою пошту.');
        }

        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);

        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);

        $user->setPasswordResetToken($tokenHash);
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate); //@TODO Створити поле для EmailConfirmation
        $this->entityManager->flush();

        $httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $emailConfirmation = 'http://' . $httpHost . '/email-confirmation?token=' . $token . "&email=" . $user->getEmail();

        // Produce HTML of password reset email
        $bodyHtml = $this->viewRenderer->render(
            'user/email/email-confirmation',
            ['emailConfirmation' => $emailConfirmation,]);


        $option = [
            'subjectEmail' => 'Підтвердженння електронної пошти.',
            'bodyHtml' => $bodyHtml,
        ];

        if ( true == $this->mailManager->sendMail($user, $option)) {
            echo 'Лист для підтвердженння електронної поштинадіслано, превірте пошту : ' . $user->getEmail();
        }


    }

    public function generatePasswordResetToken($user)
    {
        if ($user->getStatus() != User::STATUS_ACTIVE) {
            throw new Exception('Cannot generate password reset token for inactive user ' . $user->getEmail());
        }

        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);

        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);

        $user->setPasswordResetToken($tokenHash);
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);
        $this->entityManager->flush();

        $httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token . "&email=" . $user->getEmail();

        // Produce HTML of password reset email
        $bodyHtml = $this->viewRenderer->render(
            'user/email/reset-password-email',
            ['passwordResetUrl' => $passwordResetUrl,]);


        $option = [
            'subjectEmail' => 'Скидання пароля.',
            'bodyHtml' => $bodyHtml,
        ];

        if ( true == $this->mailManager->sendMail($user, $option)) {
            echo 'Лист для скидання пароля надіслано, превірте пошту : ' . $user->getEmail();
        }

    }

    public function validatePasswordResetToken($email, $passwordResetToken): bool
    {
        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneByEmail($email);

        if($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Check that token hash matches the token hash in our DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $user->getResetPasswordToken();

        if (!$bcrypt->verify($passwordResetToken, $tokenHash)) {
            return false; // mismatch
        }

        // Check that token was created not too long ago.
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);

        $currentDate = strtotime('now');

        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }

        return true;
    }

    public function setNewPasswordByToken($email, $passwordResetToken, $newPassword): bool
    {
        if (!$this->validatePasswordResetToken($email, $passwordResetToken)) {
            return false;
        }

        // Find user with the given email.
        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneByEmail($email);

        if ($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Set new password for user
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Remove password reset token
        $user->setPasswordResetToken('');
//        $user->setStatus(User::STATUS_ACTIVE);
//
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method is used to change the password for the given user. To change the password,
     * one must know the old password.
     */
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];

        // Check that old password is correct
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }

        $newPassword = $data['new_password'];

        // Check password length
        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }

        // Set new password for user
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Apply changes
        $this->entityManager->flush();

        return true;
    }

    /**
     * Checks whether an active user with given email address already exists in the database.
     */
    public function checkUserExists($email): bool
    {

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        return $user !== null;
    }

    /**
     * @throws Exception
     */
    public function activateUser($email, $token): bool
    {

        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneByEmail($email);

        if($user == null || $user->getStatus() == User::STATUS_ACTIVE) {
            throw new Exception('Користувача не знайдено або він вже активований');
        }

        // Check that token hash matches the token hash in our DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $user->getResetPasswordToken();

        if (!$bcrypt->verify($token, $tokenHash)) {
            throw new Exception('Недійсний token');
        }

        // Remove password reset token
        $user->setPasswordResetToken('');
        $user->setStatus(User::STATUS_ACTIVE);
//
        $this->entityManager->flush();

        return true;
    }
}