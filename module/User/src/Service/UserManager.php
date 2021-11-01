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
    private $permissionManager;
    private $viewRenderer;
    private $config;

    public function __construct($entityManager, $roleManager, $permissionManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->roleManager = $roleManager;
        $this->permissionManager = $permissionManager;
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
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

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

        //@TODO Винести то колись звідси
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'smmithadam@gmail.com';                     //SMTP username
            $mail->Password   = 'favorite_world';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('admin@example.com', 'Administration');
            $mail->addAddress($user->getEmail(), $user->getFullName());     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Reset Password !!! ';
            $mail->Body    = $bodyHtml;

            $mail->send();
            echo '<p> Лист надіслано. За декілька хвилин перевірте свою  адресу <b> ' .  $user->getEmail() . '</b></p>';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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

        //@TODO Винести то колись звідси
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'smmithadam@gmail.com';                     //SMTP username
            $mail->Password   = 'favorite_world';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('admin@example.com', 'Administration');
            $mail->addAddress($user->getEmail(), $user->getFullName());     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Reset Password !!! ';
            $mail->Body    = $bodyHtml;

            $mail->send();
            echo '<p> Лист надіслано. За декілька хвилин перевірте свою  адресу <b> ' .  $user->getEmail() . '</b></p>';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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