<?php

namespace EasyApiJwtAuthentication\Services\User;

use EasyApiBundle\Services\AbstractService;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager extends AbstractService
{
    public function __construct(ContainerInterface $container, TokenStorageInterface $tokenStorage, protected RefreshTokenManagerInterface $refreshTokenManager)
    {
        parent::__construct($container, $tokenStorage);
    }

    /**
     * Generate token and refresh token for a user.
     *
     * @param UserInterface $user
     *
     * @return array
     *
     * @throws \Exception
     */
    public function generateToken(UserInterface $user): array
    {
        // Generate new Token
        $jwtToken = $this->get('app.jwt_authentication.jws_provider')->generateTokenByUser($user);

        $refreshToken = $this->refreshTokenManager->create();
        $refreshToken->setUsername($user->getUsername());
        $refreshToken->setRefreshToken();
        $refreshToken->setValid((new \DateTime())->modify('+30 days'));

        $this->refreshTokenManager->save($refreshToken);

        return [
            'token' => $jwtToken->getToken(),
            'refresh_token' => $refreshToken->getRefreshToken(),
        ];
    }
}
