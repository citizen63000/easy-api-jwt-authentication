<?php

namespace EasyApiJwtAuthentication\Services\JWS;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\KeyLoader\KeyLoaderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\CreatedJWS;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Namshi\JOSE\JWS;
use EasyApiBundle\Services\JWS\JWSProvider as BasicJWSProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class JWSProvider extends BasicJWSProvider
{
    /** @var int */
    protected int $ttl;

    /** @var TokenStorageInterface */
    protected TokenStorageInterface $tokenStorage;

    /** @var EntityManager */
    protected EntityManager $em;

    /** @var string */
    protected string $userClass;

    /** @var string */
    protected string $userIdentityField;

    /**
     * JWSProvider constructor.
     * @param KeyLoaderInterface $keyLoader
     * @param string $cryptoEngine
     * @param string $signatureAlgorithm
     * @param string $authorizationHeaderPrefix
     * @param int $ttl
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManager $entityManager
     * @param string $userClass
     * @param string $userIdentityField
     */
    public function __construct(KeyLoaderInterface $keyLoader, string $cryptoEngine, string $signatureAlgorithm, string $authorizationHeaderPrefix, int $ttl, TokenStorageInterface $tokenStorage, EntityManager $entityManager, string $userClass, string $userIdentityField)
    {
        parent::__construct($keyLoader, $cryptoEngine, $signatureAlgorithm, $authorizationHeaderPrefix);
        $this->ttl = $ttl;
        $this->tokenStorage = $tokenStorage;
        $this->em = $entityManager;
        $this->userClass = $userClass;
        $this->userIdentityField = $userIdentityField;
    }

    /**
     * @param UserInterface $user
     * @return CreatedJWS
     */
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

        if (null === $user || (is_string($user) && $user === 'anon.')) {
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
