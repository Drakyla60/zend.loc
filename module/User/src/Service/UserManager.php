<?php

namespace User\Service;

use Exception;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Math\Rand;
use PHPMailer\PHPMailer\PHPMailer;
use User\Entity\User;

class UserManager
{

    private $entityManager;
    private $viewRenderer;

    public function __construct($entityManager, $viewRenderer)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
    }

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

    public function updateUser(User $user, $data): User
    {
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);
        $user->setStatus($data['status']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
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

    public function createAdminUserIfNotExists()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user==null) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('Secur1ty');
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
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
}