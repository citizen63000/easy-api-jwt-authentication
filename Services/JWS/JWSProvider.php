<?php

namespace EasyApiJwtAuthentication\Services\JWS;

use EasyApiBundle\Services\JWS\JWSProvider as BasicJWSProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\CreatedJWS;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

class JWSProvider extends BasicJWSProvider
{
    public function generateTokenByUser(UserInterface $user): CreatedJWS
    {
        $identityGetter = 'get'.ucfirst($this->userIdentityField);

        return $this->create(['roles' => $user->getRoles(), $this->userIdentityField => $user->$identityGetter()]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $payload, array $header = []): ?CreatedJWS
    {
        return parent::create($payload + ['jti' => Uuid::uuid4()], $header);
    }
}
