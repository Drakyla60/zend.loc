<?php

namespace User\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    public function getStatusAsString(): string
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    public function setDateCreated(string $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    public function getResetPasswordToken(): string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(string $token)
    {
        $this->passwordResetToken = $token;
    }

    public function getPasswordResetTokenCreationDate(): string
    {
        return $this->passwordResetTokenCreationDate;
    }

    public function setPasswordResetTokenCreationDate(string $date)
    {
        $this->passwordResetTokenCreationDate = $date;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getRolesAsString(): string
    {
        $roleList = '';

        $count = count($this->roles);
        $i = 0;
        foreach ($this->roles as $role) {
            $roleList .= $role->getName();
            if ($i < $count - 1)
                $roleList .= ', ';
            $i++;
        }

        return $roleList;
    }

    public function addRole($role)
    {
        $this->roles->add($role);
    }
}