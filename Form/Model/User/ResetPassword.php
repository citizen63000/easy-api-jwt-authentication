<?php

namespace EasyApiJwtAuthentication\Form\Model\User;

class ResetPassword
{
    private string $username;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }
}
