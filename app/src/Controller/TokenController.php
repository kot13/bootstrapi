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

final class TokenController extends BaseController
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
     * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
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

        $this->validationRequest($params, 'token', new GetTokenRequest());

        $user = User::findUserByEmail($params['data']['attributes']['username']);

        if ($user && password_verify($params['data']['attributes']['password'], $user->password)) {
            $accessToken = AccessToken::createToken(
                $request->getUri()->getHost(),
                $user,
                $this->settings['params']['tokenExpire']
            );
            $refreshToken = RefreshToken::createToken($user);
        } else {
            throw new JsonException('token', 400, 'Invalid Attribute', 'Invalid password or username');
        };

        $result = $this->buildResponse($accessToken, $refreshToken);

        return $this->renderer->jsonApiRender($response, 200, json_encode($result));
    }

    /**
     * @api {post} /refresh-token Обновление токена
     * @apiName RefreshToken
     * @apiGroup Token
     *
     * @apiDescription Метод для обновления access_token по refresh_token
     *
     * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
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

        $this->validationRequest($params, 'token', new RefreshTokenRequest());

        $user = RefreshToken::getUserByToken($params['data']['attributes']['refresh_token']);

        if ($user) {
            $token = AccessToken::createToken(
                $request->getUri()->getHost(),
                $user,
                $this->settings['params']['tokenExpire']
            );
            $refreshToken = RefreshToken::createToken($user);
        } else {
            throw new JsonException('token', 400, 'Invalid Attribute', 'Invalid refresh_token');
        };

        $result = $this->buildResponse($token, $refreshToken);

        return $this->renderer->jsonApiRender($response, 200, json_encode($result));
    }

    /**
     * @param string $token
     * @param string $refreshToken
     *
     * @return array
     */
    private function buildResponse($token, $refreshToken)
    {
        return [
            'access_token'  => $token,
            'expires_in'    => $this->settings['params']['tokenExpire'],
            'token_type'    => self::TOKEN_TYPE,
            'refresh_token' => $refreshToken,
        ];
    }
}
