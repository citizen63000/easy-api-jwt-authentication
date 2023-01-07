<?php

namespace EasyApiJwtAuthentication\Controller;

use EasyApiBundle\Controller\AbstractApiController;
use EasyApiJwtAuthentication\Services\User\UserManager;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/", name="authentication")
 * @OA\Tag(name="Authentication")
 */
class AuthenticationController extends AbstractApiController
{
    protected TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function authenticateAction()
    {
    }

    /**
     * User logout.
     * @Route("/logout", methods={"POST"}, name="_logout", )
     * @OA\Response(response="200", description="Successful operation"),
     * @OA\Response(response="401", description="Unauthorized"),
     * @OA\Response(response="403", description="Forbidden"),
     */
    public function logoutAction(): Response
    {
        $this->tokenStorage->setToken();

        return $this->renderResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Add dynamically Filter service.
     *
     * @return string[]
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), ['app.user.manager' => UserManager::class]);
    }
}
