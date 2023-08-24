<?php

namespace EasyApiJwtAuthentication\Util\Tests\functions;

use EasyApiTests\crud\functions\crudFunctionsTestTrait;
use Namshi\JOSE\JWS;

trait AuthenticationTestFunctionsTrait
{
    use crudFunctionsTestTrait;

    /**
     * @param array $response
     */
    protected function checkAuthenticateResponse(array $response): void
    {
        self::arrayHasKey('token', $response);
        self::arrayHasKey('refreshToken', $response);
        self::checkPayloadContent($token = JWS::load($response['token'])->getPayload());
    }

    /**
     * @param array $payload
     */
    protected function checkPayloadContent(array $payload): void
    {
        self::assertArrayHasKey('iat', $payload);
        self::assertArrayHasKey('exp', $payload);
        self::assertEquals($payload['iat'] + self::$container->getParameter('lexik_jwt_authentication.token_ttl'), $payload['exp']);
    }
}
