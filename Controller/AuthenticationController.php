<?php

namespace EasyApiJwtAuthentication\Controller;

use EasyApiBundle\Controller\AbstractApiController;
use EasyApiJwtAuthentication\Services\User\UserManager;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(name="Authentication")
 */
class AuthenticationController extends AbstractApiController
{
    public function authenticateAction()
    {

    }

    /**
     * User logout.
     *
     * @OA\Response(response=200, description="Successful operation"),
     * @OA\Response(response="401", ref="#/definitions/401"),
     * @OA\Response(response="403", ref="#/definitions/403"),
     * @OA\Response(response="415", ref="#/definitions/415"),
     * @OA\Response(response="422", ref="#/definitions/422")
     *
     * @return Response
     */
    public function logoutAction(): Response
    {
        $this->get('security.token_storage')->setToken();
        $this->get('session')->invalidate();

        return $this->renderResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Add dynamically Filter service
     * @return string[]
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), ['app.user.manager' => UserManager::class]);
    }
}
