<?php

namespace EasyApiJwtAuthentication\Controller;

use EasyApiBundle\Services\User\UserManager;
use EasyApiBundle\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * @SWG\Tag(name="Authentication")
 */
class AuthenticationController extends AbstractApiController
{
    /**
     * User logout.
     *
     * @SWG\Response(response=200, description="Successful operation"),
     * @SWG\Response(response="401", ref="#/definitions/401"),
     * @SWG\Response(response="403", ref="#/definitions/403"),
     * @SWG\Response(response="415", ref="#/definitions/415"),
     * @SWG\Response(response="422", ref="#/definitions/422")
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