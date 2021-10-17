<?php

namespace EasyApiJwtAuthentication\Form\Model\User;

class ResetPassword
{
    /**
     * @var string
     */
    private string $username;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
}
