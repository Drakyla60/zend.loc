<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Цей клас представляє зареєстрованого користувача.
 * @ORM\Entity(repositoryClass="\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    // Константи для активного і неактивного користувача
    const STATUS_ACTIVE  = 1;
    const STATUS_RETIRED = 2;

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column(name="email")
     */
    protected string $email;

    /**
     * @ORM\Column(name="full_name")
     */
    protected string $fullName;

    /**
     * @ORM\Column(name="password")
     */
    protected string $password;

    /**
     * @ORM\Column(name="status")
     */
    protected int $status;

    /**
     * @ORM\Column(name="date_created")
     */
    protected string $dateCreated;

    /**
     * @ORM\Column(name="pwd_reset_token")
     */
    protected string $passwordResetToken;

    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")
     */
    protected string $passwordResetTokenCreationDate;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    /**
     * @return string
     */
    public function getStatusAsString(): string
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    /**
     * @param string $dateCreated
     */
    public function setDateCreated(string $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return $this->passwordResetToken;
    }

    /**
     * @param string $token
     */
    public function setPasswordResetToken(string $token)
    {
        $this->passwordResetToken = $token;
    }

    /**
     * @return string
     */
    public function getPasswordResetTokenCreationDate(): string
    {
        return $this->passwordResetTokenCreationDate;
    }

    /**
     * @param string $date
     */
    public function setPasswordResetTokenCreationDate(string $date)
    {
        $this->passwordResetTokenCreationDate = $date;
    }
}