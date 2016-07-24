<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Document\Error;

use Firebase\JWT\JWT;
use App\Model\User;

final class TokenController extends BaseController
{
    protected static function createToken($request)
    {
        // TODO Получать из конфига tokenExpire
        $tokenExpire = 3600;

        $secret_key = getenv('SECRET_KEY');
        $token = array(
            'iss' => getenv('AUTH_ISS'),
            'aud' => $request->getUri()->getHost(),
            'iat' => time(),
            'exp' => time() + $tokenExpire,
        );
        $jwt = JWT::encode($token, $secret_key);
        return $jwt;
    }

    protected static function getWhiteListAudience()
    {
        // TODO Получать из конфига список host'ов
        return [
            'skeleton.dev',
            'localhost',
        ];
    }

    public static function validateToken($token)
    {
        try {
            $payload = JWT::decode($token, getenv('SECRET_KEY'), ['HS256']);
            $audiences = self::getWhiteListAudience();
            if (!in_array($payload->aud, $audiences)) {
                return false;
            }
            return true;
        } catch (\Exception $e){
            return false;
        }
    }

    public function auth($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $validator = $this->validation->make($params, $rules);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            $error = new Error(
                'token',
                null,
                '400',
                '400',
                'Invalid Attribute',
                $messages
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $user = User::findUserByEmail($params['username']);

        if ($user && password_verify($params['password'], $user->password)) {
            $token = self::createToken($request);
            $user->access_token = md5($token);
            $user->save();
        } else {
            $error = new Error(
                'token',
                null,
                '401',
                '401',
                'Invalid Attribute',
                'Invalid password or username'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 401, $result);
        };

        $result = [
            'access_token' => $token,
            'user' => $user->toArray()
        ];

        return $this->renderer->jsonApiRender($response, 200, json_encode($result));
    }
}