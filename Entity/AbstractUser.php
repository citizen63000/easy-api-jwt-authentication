<?php

namespace EasyApiJwtAuthentication\Entity;

use Doctrine\ORM\Mapping as ORM;
use EasyApiBundle\Entity\User\AbstractUser as AbstractBaseUser;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

abstract class AbstractUser extends AbstractBaseUser implements PasswordHasherAwareInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $passwordHasherName = null;

    #[ORM\Column(type: 'string', nullable: false, options: ['default' => ''])]
    protected string $password = '';

    #[ORM\Column(type: 'boolean', nullable: true)]
    protected bool $anonymous = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?\DateTime $lastLogin = null;

    /**
     * @see PasswordHasherAwareInterface
     * @return string|null
     */
    public function getPasswordHasherName(): ?string
    {
        return $this->passwordHasherName;
    }

    public function setPasswordHasherName(?string $passwordHasherName): void
    {
        $this->passwordHasherName = $passwordHasherName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    public function setAnonymous(bool $anonymous): void
    {
        $this->anonymous = $anonymous;
    }

    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }
}
