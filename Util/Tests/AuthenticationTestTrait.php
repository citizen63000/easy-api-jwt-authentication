<?php

namespace EasyApiJwtAuthentication\Util\Tests;

use EasyApiJwtAuthentication\Util\Tests\functions\AuthenticationTestFunctionsTrait;
use Symfony\Component\HttpFoundation\Response;

trait AuthenticationTestTrait
{
    use AuthenticationTestFunctionsTrait;

    protected static $authenticateRouteName = 'api_login';
    protected static $refreshTokenRouteName = 'gesdinet_jwt_refresh_token';
    protected static $logoutRouteName = 'authentication_logout';

    protected static function initExecuteSetupOnAllTest()
    {
        static::$executeSetupOnAllTest = false;
    }

    public function testAuthenticateWithGoodCredentials()
    {
        $params = ['username' => static::USER_TEST_USERNAME, 'password' => static::USER_TEST_PASSWORD];

        // Request
        $apiOutput = self::httpPost(['name' => static::$authenticateRouteName], $params, false);

        // Assert token
        static::assertEquals(Response::HTTP_OK, $apiOutput->getStatusCode());
        self::checkAuthenticateResponse($apiOutput->getData());
    }

    public function testAuthenticateWithBadCredentials()
    {
        $params = [
            'username' => 'thisusernotexist',
            'password' => 'thisusernotexist',
        ];

        // Request
        $apiOutput = self::httpPost(['name' => static::$authenticateRouteName], $params, false);
        static::assertEquals(Response::HTTP_UNAUTHORIZED, $apiOutput->getStatusCode());
        static::assertEquals(['errors' => ['core.error.bad_credentials']], $apiOutput->getData());
    }

    public function testRefreshToken()
    {
        $params = ['username' => static::USER_TEST_USERNAME, 'password' => static::USER_TEST_PASSWORD];

        // get refreshToken
        $apiOutput = self::httpPost(['name' => static::$authenticateRouteName], $params, false);
        static::assertEquals(Response::HTTP_OK, $apiOutput->getStatusCode());
        $result = $apiOutput->getData();
        self::arrayHasKey('refresh_token', $result);

        // test refreshToken route
        $apiOutput = self::httpPost(['name' => static::$refreshTokenRouteName], ['refresh_token' => $result['refresh_token']], false);
        static::assertEquals(Response::HTTP_OK, $apiOutput->getStatusCode());
        self::checkAuthenticateResponse($apiOutput->getData());
    }

    public function testRefreshTokenWithBadRefreshToken()
    {
        // Request
        $apiOutput = self::httpPost(['name' => static::$refreshTokenRouteName], ['refreshToken' => 'imfakerefreshtoken'], false);
        static::assertEquals(Response::HTTP_UNAUTHORIZED, $apiOutput->getStatusCode());
        static::assertEquals(['errors' => ['core.error.bad_credentials']], $apiOutput->getData());
    }

    public function testLogout()
    {
        $apiOutput = self::httpPost(['name' => static::$logoutRouteName]);
        static::assertEquals(Response::HTTP_NO_CONTENT, $apiOutput->getStatusCode());
    }
}