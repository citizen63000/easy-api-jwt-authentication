<?php

namespace EasyApiJwtAuthentication\Entity;

use Doctrine\ORM\Mapping as ORM;
use EasyApiBundle\Entity\User\AbstractUser as AbstractBaseUser;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

abstract class AbstractUser extends AbstractBaseUser implements PasswordHasherAwareInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $passwordHasherName = null;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    protected ?string $password = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected bool $anonymous = false;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", nullable=true)
     */
    protected ?\DateTime $lastLogin = null;

    /**
     * @see PasswordHasherAwareInterface
     * @return string|null
     */
    public function getPasswordHasherName(): ?string
    {
        return $this->passwordHasherName;
    }

    /**
     * @param string|null $passwordHasherName
     */
    public function setPasswordHasherName(?string $passwordHasherName): void
    {
        $this->passwordHasherName = $passwordHasherName;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    /**
     * @param bool $anonymous
     */
    public function setAnonymous(bool $anonymous): void
    {
        $this->anonymous = $anonymous;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     */
    public function setLastLogin(?\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }
}
