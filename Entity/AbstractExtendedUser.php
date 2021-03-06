<?php

namespace EasyApiJwtAuthentication\Entity;

use Doctrine\ORM\Mapping as ORM;
use EasyApiBundle\Entity\User\AbstractUser;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;

abstract class AbstractExtendedUser extends AbstractUser implements EncoderAwareInterface
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected string $encoder = 'default';

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
     * @ORM\Column(type="date")
     */
    protected ?\DateTime $lastLogin = null;

    /**
     * @return string
     */
    public function getEncoder(): string
    {
        return $this->encoder;
    }

    /**
     * @param string $encoder
     */
    public function setEncoder(string $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return string
     */
    public function getEncoderName(): string
    {
        return $this->encoder;
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
