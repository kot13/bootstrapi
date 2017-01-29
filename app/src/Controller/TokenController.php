<?php
namespace App\Controller;

use App\Model\RefreshToken;
use App\Requests\GetTokenRequest;
use App\Requests\RefreshTokenRequest;
use Firebase\JWT\JWT;
use App\Model\User;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Common\JsonException;
use App\Common\Helper;

final class TokenController extends BaseController
{
    const TOKEN_TYPE = 'Bearer';

    /**
     * @param string $token
     * @param array  $whiteList
     *
     * @return bool
     */
    public static function validateToken($token, $whiteList = [])
    {
        try {
            $payload = JWT::decode($token, getenv('SECRET_KEY'), ['HS256']);
            return in_array($payload->aud, $whiteList);
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * @api {post} /token Получение токена
     * @apiName CreateToken
     * @apiGroup Token
     *
     * @apiDescription Метод для получения авторизационного токена. Он отправляется в заголовке запроса:
     *
     * Authorization: Bearer xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
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
     * @return mixed
     * @throws JsonException
     */
    public function getToken(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $this->validationRequest($params, 'token', new GetTokenRequest());

        $user = User::findUserByEmail($params['data']['attributes']['username']);

        if ($user && password_verify($params['data']['attributes']['password'], $user->password)) {
            $token = self::createToken($request->getUri()->getHost(), $this->settings['params']['tokenExpire']);

            $user->access_tokens()->create([
                'access_token' => md5($token),
                'created_at'   => date('Y-m-d H:i:s'),
            ]);

            $refreshToken = self::createRefreshToken();

            $user->refresh_tokens()->create([
                'refresh_token' => $refreshToken,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        } else {
            throw new JsonException('token', 400, 'Invalid Attribute', 'Invalid password or username');
        };

        $result = $this->buildResponse($token, $refreshToken);

        return $this->renderer->jsonApiRender($response, 200, json_encode($result));
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
     * @return mixed
     * @throws JsonException
     */
    public function refreshToken(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $this->validationRequest($params, 'token', new RefreshTokenRequest());

        $user = RefreshToken::getUserByRefreshToken($params['data']['attributes']['refresh_token']);

        if ($user) {
            $token = self::createToken($request->getUri()->getHost(), $this->settings['params']['tokenExpire']);

            $user->access_tokens()->create([
                'access_token' => md5($token),
                'created_at'   => date('Y-m-d H:i:s'),
            ]);

            $refreshToken = self::createRefreshToken();

            $user->refresh_tokens()->create([
                'refresh_token' => $refreshToken,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
        } else {
            throw new JsonException('token', 400, 'Invalid Attribute', 'Invalid refresh_token');
        };

        $result = $this->buildResponse($token, $refreshToken);

        return $this->renderer->jsonApiRender($response, 200, json_encode($result));
    }

    /**
     * @param string $host
     * @param int    $tokenExpire
     *
     * @return string
     */
    private static function createToken($host, $tokenExpire = 3600)
    {
        $secret_key = getenv('SECRET_KEY');
        $token      = [
            'iss' => getenv('AUTH_ISS'),
            'aud' => $host,
            'iat' => time(),
            'exp' => time() + $tokenExpire,
        ];

        $jwt = JWT::encode($token, $secret_key);
        return $jwt;
    }

    /**
     * @return string
     */
    private static function createRefreshToken()
    {
        return md5(Helper::generateRandomString() . '_' . time());
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
