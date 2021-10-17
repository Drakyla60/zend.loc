<?php

namespace User\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Laminas\Mail\Transport\Sendmail as SendmailTransport;
use Exception;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Math\Rand;
use Laminas\Mime\Message;
use Laminas\Mime\Part;
use User\Entity\User;

class UserManager
{

    private EntityManager $entityManager;
    private $viewRenderer;

    public function __construct($entityManager, $viewRenderer)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * @throws Exception
     */
    public function addUser($data): User
    {
        if ($this->checkUserExists($data['email'])) {
            throw new Exception("User with email address " .
                $data['$email'] . " already exists");
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

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $user;
    }

    public function validatePassword(User $user, $password): bool
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }
        return false;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function createAdminUserIfNotExists()
    {
        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy([]);

        if (null == $user) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('password');
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function generatePasswordResetToken($user)
    {
        if ($user->getStatus() != User::STATUS_ACTIVE) {
            throw new \Exception('Cannot generate password reset token for inactive user ' . $user->getEmail());
        }

        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);

        // Encrypt the token before storing it in DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);

        // Save token to DB
        $user->setPasswordResetToken($tokenHash);

        // Save token creation date to DB.
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);

        // Apply changes to DB.
        $this->entityManager->flush();

        // Send an email to user.
        $subject = 'Password Reset';

        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token . "&email=" . $user->getEmail();

        // Produce HTML of password reset email
        $bodyHtml = $this->viewRenderer->render(
            'user/email/reset-password-email',
            [
                'passwordResetUrl' => $passwordResetUrl,
            ]);

        $html = new Part($bodyHtml);
        $html->type = "text/html";

        $body = new Message();
        $body->addPart($html);

        $mail = new \Laminas\Mail\Message();
        $mail->setEncoding('UTF-8');
        $mail->setBody($body);
        $mail->setFrom('no-reply@example.com', 'User Demo');
        $mail->addTo($user->getEmail(), $user->getFullName());
        $mail->setSubject($subject);

        // Setup SMTP transport
        $transport = new SendmailTransport();
//        $options   = new SmtpOptions($this->config['smtp']);
//        $transport->setOptions($options);

        $transport->send($mail);
    }

    public function validatePasswordResetToken($email, $passwordResetToken)
    {
        // Find user by email.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        if ($user == null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Check that token hash matches the token hash in our DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $user->getPasswordResetToken();

        if (!$bcrypt->verify($passwordResetToken, $tokenHash)) {
            return false; // mismatch
        }

        // Check that token was created not too long ago.
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);

        $currentDate = strtotime('now');

        if ($currentDate - $tokenCreationDate > 24 * 60 * 60) {
            return false; // expired
        }

        return true;
    }

    public function setNewPasswordByToken($email, $passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($email, $passwordResetToken)) {
            return false;
        }

        // Find user with the given email.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        if ($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Set new password for user
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Remove password reset token
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);

        $this->entityManager->flush();

        return true;
    }
}