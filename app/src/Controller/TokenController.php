<?php

namespace App\Controller;

use App\Requests\GetTokenRequest;
use App\Requests\RefreshTokenRequest;
use App\Model\User;
use App\Model\AccessToken;
use App\Model\RefreshToken;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Common\JsonException;

class TokenController extends BaseController
{
    const TOKEN_TYPE = 'Bearer';

    /**
     * @api {post} /token Получение токена
     * @apiName CreateToken
     * @apiGroup Token
     *
     * @apiDescription Метод для получения авторизационного токена. Токен необходим для выполнения запросов к АПИ.
     * Полученный токен отправляется в заголовке запроса:
     * <br/>
     * <strong>Authorization: Bearer xxxxxxxxxxxxxxxxxxxxxxxxxxxxx</strong>
     *
     * @apiParam {String} username Логин
     * @apiParam {String} password Пароль
     *
     * @apiParamExample {json} Пример запроса:
     *    {
     *      "data":{
     *        "attributes":{
     *          "username":"admin@example.com",
     *          "password": "qwerty"
     *        }
     *      }
     *    }
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOmZhbHNlLCJhdWQiOiJza2VsZXRvbi5kZXYiLCJpYXQiOjE0NzY0Mjk4NjksImV4cCI6MTQ3NjQzMzQ2OX0.NJn_-lK28kEZyZqygLr6B-FZ2zC2-1unStayTGicP5g",
     *       "expires_in": 3600,
     *       "token_type": "Bearer",
     *       "refresh_token": "092ea7e36f6b9bf462cb3ca1f6f86b80"
     *     }
     *
     * @apiUse StandardErrors
     */
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function getToken(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $this->validateRequestParams($params, 'token', new GetTokenRequest());

        $user = User::findUserByEmail($params['data']['attributes']['username']);
        if (!$user || !password_verify($params['data']['attributes']['password'], $user->password)) {
            throw new JsonException('token', 400, 'Invalid Attribute', 'Invalid password or username');
        }

        return $this->buildTokens($request, $response, $user);
    }

    /**
     * @api {post} /refresh-token Обновление токена
     * @apiName RefreshToken
     * @apiGroup Token
     *
     * @apiDescription Метод для обновления access_token по refresh_token
     *
     * @apiParam {String} refresh_token Токен для обновления
     *
     * @apiParamExample {json} Пример запроса:
     *    {
     *      "data":{
     *        "attributes":{
     *          "refresh_token":"092ea7e36f6b9bf462cb3ca1f6f86b80"
     *        }
     *      }
     *    }
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOmZhbHNlLCJhdWQiOiJza2VsZXRvbi5kZXYiLCJpYXQiOjE0NzY0Mjk4NjksImV4cCI6MTQ3NjQzMzQ2OX0.NJn_-lK28kEZyZqygLr6B-FZ2zC2-1unStayTGicP5g",
     *       "expires_in": 3600,
     *       "token_type": "Bearer",
     *       "refresh_token": "092ea7e36f6b9bf462cb3ca1f6f86b80"
     *     }
     *
     * @apiUse StandardErrors
     */

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function refreshToken(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $this->validateRequestParams($params, 'token', new RefreshTokenRequest());

        $user = RefreshToken::getUserByToken($params['data']['attributes']['refresh_token']);
        if (!$user) {
            throw new JsonException('token', 400, 'Invalid Attribute', 'Invalid refresh_token');
        }

        return $this->buildTokens($request, $response, $user);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param User $user
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function buildTokens(Request $request, Response $response, User $user)
    {
        $accessToken = AccessToken::createToken(
            $user,
            $request->getUri()->getHost(),
            $this->settings['accessToken']
        );
        $refreshToken = RefreshToken::createToken($user);

        $result = [
            'token_type'    => self::TOKEN_TYPE,
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in'    => $this->settings['accessToken']['ttl'],
        ];

        return $this->apiRenderer->jsonResponse($response, 200, json_encode($result));
    }
}
