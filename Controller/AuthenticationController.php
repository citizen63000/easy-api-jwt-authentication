<?php

namespace EasyApiJwtAuthentication\Controller;

use EasyApiBundle\Controller\AbstractApiController;
use EasyApiJwtAuthentication\Services\User\UserManager;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @OA\Tag(name="Authentication")
 */
#[Route(path: '/', name: 'authentication')]
class AuthenticationController extends AbstractApiController
{
    public function __construct(protected TokenStorageInterface $tokenStorage, protected UserManager $userManager)
    {
    }

//    #[Route(path: '/authenticate', name: '_authenticate', methods: ['POST'])]
    public function authenticateAction()
    {
    }

    /**
     * User logout.
     * @OA\Response(response="200", description="Successful operation"),
     * @OA\Response(response="401", description="Unauthorized"),
     * @OA\Response(response="403", description="Forbidden"),
     */
    #[Route(path: '/logout', name: '_logout', methods: ['POST'])]
    public function logoutAction(): Response
    {
        $this->tokenStorage->setToken();

        return $this->renderResponse(null, Response::HTTP_NO_CONTENT);
    }
}
