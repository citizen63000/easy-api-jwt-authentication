<?php

namespace EasyApiJwtAuthentication\Services\JWS;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\KeyLoader\KeyLoaderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\CreatedJWS;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Namshi\JOSE\JWS;
use EasyApiBundle\Services\JWS\JWSProvider as BasicJWSProvider;

class JWSProvider extends BasicJWSProvider
{
    /** @var string */
    protected $userClass;

    /** @var string */
    protected $userIdentityField;

    /**
     * @param KeyLoaderInterface $keyLoader
     * @param string $cryptoEngine
     * @param string $signatureAlgorithm
     * @param int $ttl
     * @param string $authorizationHeaderPrefix
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManager $entityManager
     * @param string $userClass
     * @param string $userIdentityField
     */
    public function __construct(KeyLoaderInterface $keyLoader, $cryptoEngine, $signatureAlgorithm, $ttl, $authorizationHeaderPrefix, TokenStorageInterface $tokenStorage, EntityManager $entityManager, string $userClass, string $userIdentityField)
    {
        parent::__construct($keyLoader, $cryptoEngine, $signatureAlgorithm, $ttl,$authorizationHeaderPrefix, $tokenStorage, $entityManager);
        $this->userClass = $userClass;
        $this->userIdentityField = $userIdentityField;
    }

    /**
     * @param User $user
     * @return CreatedJWS
     */
    public function generateTokenByUser(User $user)
    {
        $identityGetter = 'get'.ucfirst($this->userIdentityField);
        return $this->create(['roles' => $user->getRoles(), $this->userIdentityField => $user->$identityGetter()]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $payload, array $header = [])
    {
        $jws = new JWS([
            'alg' => $this->signatureAlgorithm,
            'typ' => $this->authorizationHeaderPrefix,
        ], $this->cryptoEngine);

        $token = $this->tokenStorage->getToken();
        $user = (null !== $token) ? $token->getUser() : null;

        if (null === $user || (is_string($user) && $user === 'anon.')) {
            $user = $this->em->getRepository($this->userClass)->findOneBy([$this->userIdentityField => $payload[$this->userIdentityField]]);
        }

        if ($user instanceof User) {
            $jws->setPayload($payload + ['exp' => time() + $this->ttl, 'iat' => time(), 'displayName' => $user->__toString()]);
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