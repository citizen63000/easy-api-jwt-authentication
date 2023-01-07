<?php

namespace EasyApiJwtAuthentication\Services\JWS;

use EasyApiBundle\Services\JWS\JWSProvider as BasicJWSProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\CreatedJWS;
use Namshi\JOSE\JWS;
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
    public function create(array $payload, array $header = []): CreatedJWS
    {
        $jws = new JWS([
            'alg' => $this->signatureAlgorithm,
            'typ' => $this->authorizationHeaderPrefix,
        ], $this->cryptoEngine);

        $token = $this->tokenStorage->getToken();
        $user = (null !== $token) ? $token->getUser() : null;

        if (null === $user || (is_string($user) && 'anon.' === $user)) {
            $user = $this->em->getRepository($this->userClass)->findOneBy([$this->userIdentityField => $payload[$this->userIdentityField]]);
        }

        if ($user instanceof UserInterface) {
            $additionnalPayload = [
                'exp' => time() + $this->ttl,
                'iat' => time(),
                'jti' => Uuid::uuid4(),
                'displayName' => $user->__toString(),
            ];
            $jws->setPayload($payload + $additionnalPayload);
        } else {
            $jws->setPayload($payload);
        }

        $jws->sign(
            $this->keyLoader->loadKey('private'),
            $this->keyLoader->getPassphrase()
        );

        return new CreatedJWS($jws->getTokenString(), $jws->isSigned());
    }
}
