<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;

use App\Model\User;
use App\Common\JsonException;

use Slim\Http\Request;
use Slim\Http\Response;

final class UserController extends BaseController
{
    /**
     * @api {get} /user Список пользователей
     * @apiName GetUsers
     * @apiGroup User
     *
     * @apiDescription Метод для получения списка пользователей.
     *
     * @apiHeader {String} Authorization Токен.
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *         {
     *           "type": "user",
     *           "id": "1",
     *           "attributes": {
     *             "full_name": "Тестовый пользователь",
     *             "email": "mail@example.com",
     *             "role_id": 1,
     *             "created_at": {
     *               "date": "2016-10-13 21:37:40.000000",
     *               "timezone_type": 3,
     *               "timezone": "Europe/Moscow"
     *             },
     *             "updated_at": {
     *               "date": "2016-10-13 21:37:40.000000",
     *               "timezone_type": 3,
     *               "timezone": "Europe/Moscow"
     *             },
     *             "created_by": 0,
     *             "updated_by": null,
     *             "status": 1,
     *           },
     *           "relationships": {
     *             "role": {
     *               "data": {
     *                 "type": "role",
     *                 "id": "1"
     *               }
     *             }
     *           },
     *           "links": {
     *             "self": "http://skeleton.dev/api/user/1"
     *           }
     *         }
     *       ]
     *     }
     *
     * @apiUse StandardErrors
     * @apiUse UnauthorizedError
     */

    /**
     * @api {get} /user/:id?include=role&fields[role]=name Получить пользователя
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiDescription Метод для получения пользователя.
     *
     * @apiParam {Number} id Id пользователя
     *
     * @apiHeader {String} Authorization Токен.
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "type": "user",
     *         "id": "1",
     *         "attributes": {
     *           "full_name": "Тестовый пользователь",
     *           "email": "mail@example.com",
     *           "role_id": 1,
     *           "created_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "updated_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "created_by": 0,
     *           "updated_by": null,
     *           "status": 1,
     *         },
     *         "relationships": {
     *           "role": {
     *             "data": {
     *               "type": "role",
     *               "id": "1"
     *             }
     *           }
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/user/1"
     *         }
     *       }
     *     }
     *
     * @apiUse StandardErrors
     * @apiUse UnauthorizedError
     * @apiUse NotFoundError
     */

    /**
     * @api {delete} /user/:id Удаление пользователя
     * @apiName DeleteUser
     * @apiGroup User
     *
     * @apiDescription Метод для удаления пользователя.
     *
     * @apiParam {Number} id Id пользователя
     *
     * @apiHeader {String} Authorization Токен.
     *
     * @apiSuccessExample {json} Успешно (204)
     *     HTTP/1.1 204 OK
     *
     * @apiUse UnauthorizedError
     * @apiUse StandardErrors
     * @apiUse NotFoundError
     */

    /**
     * @api {post} /user Создание пользователя
     * @apiName CreateUser
     * @apiGroup User
     *
     * @apiDescription Метод для создания нового пользователя.
     *
     * @apiParam {String} full_name Полное имя пользователя
     * @apiParam {String} email Email пользователя (уникальный)
     * @apiParam {String} password Пароль
     * @apiParam {Number} role_id Id роли пользователя
     *
     * @apiParamExample {json} Пример запроса:
     *    {
     *      "data":{
     *        "attributes":{
     *          "full_name":"Тестовый пользователь",
     *          "email": "mail@example.com",
     *          "password": "qwerty",
     *          "role_id": 1,
     *          "status": 1
     *        }
     *      }
     *    }
     *
     * @apiHeader {String} Authorization Токен.
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "type": "user",
     *         "id": "2",
     *         "attributes": {
     *           "full_name": "Тестовый пользователь",
     *           "email": "mail2@example.com",
     *           "role_id": 1,
     *           "created_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "updated_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "created_by": 1,
     *           "updated_by": null,
     *           "status": 1,
     *         },
     *         "relationships": {
     *            "role": {
     *             "data": {
     *               "type": "role",
     *               "id": "1"
     *             }
     *           }
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/user/2"
     *         }
     *       }
     *     }
     *
     * @apiUse StandardErrors
     * @apiUse UnauthorizedError
     */
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionCreate(Request $request, Response $response, $args)
    {
        $expandEntity = User::$expand;
        $params = $request->getParsedBody();

        if (!isset($params['data']['attributes'])) {
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $validator = $this->validation->make($params['data']['attributes'], User::$rules['create']);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $exist = User::exist($params['data']['attributes']['email']);

        if ($exist) {
            throw new JsonException($args['entity'], 400, 'User already exists', 'User already exists');
        }

        $user = User::create($params['data']['attributes']);
        $user->setPassword($params['data']['attributes']['password']);
        $user->save();

        $encodeEntities = [User::class => User::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result  = $encoder->encodeData($user);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    /**
     * @api {patch} /user/:id Изменение пользователя
     * @apiName UpdateUser
     * @apiGroup User
     *
     * @apiDescription Метод для изменения пользователя.
     *
     * @apiParam {String} full_name Полное имя пользователя
     * @apiParam {String} email Email пользователя (уникальный)
     * @apiParam {String} password Пароль
     * @apiParam {Number} role_id Id роли пользователя
     *
     * @apiParamExample {json} Пример запроса:
     *    {
     *      "data":{
     *        "attributes":{
     *          "full_name":"Тестовый пользователь",
     *          "email": "mail@example.com",
     *          "password": "qwerty",
     *          "role_id": 1
     *        }
     *      }
     *    }
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "type": "user",
     *         "id": 2,
     *         "attributes": {
     *           "full_name": "Тестовый пользователь",
     *           "email": "mail1@example.com",
     *           "role_id": 1,
     *           "created_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "updated_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "created_by": null,
     *           "updated_by": null,
     *           "status": 1,
     *         },
     *         "relationships": {
     *           "role": {
     *             "data": {
     *               "type": "role",
     *               "id": "1"
     *             }
     *           }
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/user/2"
     *         }
     *       }
     *     }
     *
     * @apiHeader {String} Authorization Токен.
     *
     * @apiUse StandardErrors
     * @apiUse UnauthorizedError
     * @apiUse NotFoundError
     */
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionUpdate(Request $request, Response $response, $args)
    {
        $expandEntity = User::$expand;
        $params       = $request->getParsedBody();

        if (!isset($params['data']['attributes'])) {
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $user = User::find($args['id']);

        if (!$user) {
            throw new JsonException($args['entity'], 404, 'Not found','Entity not found');
        }

        $validator = $this->validation->make($params['data']['attributes'], User::$rules['update']);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $user->update($params['data']['attributes']);

        if (isset($params['data']['attributes']['password'])) {
            $user->setPassword($params['data']['attributes']['password']);
            $user->save();
        }

        $encodeEntities = [User::class => User::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result  = $encoder->encodeData($user);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    /**
     * @api {post} /user/request-password-reset Запрос на сброс пароля
     * @apiName RequestPasswordReset
     * @apiGroup User
     *
     * @apiDescription Метод высылающий на email пользователя письмо со ссылкой для изменения пароля.
     *
     * В ссылке отправляется токен для сброса пароля. Его нужно отправить в методе /user/reset-password
     *
     * @apiParam {String} email Email пользователя
     *
     * @apiParamExample {json} Пример запроса:
     *    {
     *      "data":{
     *        "attributes":{
     *          "email": "mail@example.com"
     *        }
     *      }
     *    }
     *
     * @apiSuccessExample {json} Успешно (204)
     *     HTTP/1.1 204 OK
     *
     * @apiUse StandardErrors
     */
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionRequestResetPassword(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();

        if (!isset($params['data']['attributes'])) {
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $validator = $this->validation->make($params['data']['attributes'], ['email' => 'required|email']);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $user = User::findUserByEmail($params['data']['attributes']['email']);

        if (!$user) {
            throw new JsonException($args['entity'], 400, 'Bad request', 'Bad request');
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }

        if (!$user->save()) {
            throw new JsonException($args['entity'], 400, 'Bad request', 'Bad request');
        }

        $message = \Swift_Message::newInstance('Восстановление пароля для доступа в example.com')
            ->setFrom(['no-reply@example.com' => 'Почтовик example.com'])
            ->setTo([$user->email => $user->full_name])
            ->setBody($this->mailRenderer->render("/RequestResetPassword.php", ['host' => $this->settings['params']['host'], 'token' => $user->password_reset_token]), 'text/html');

        if ($this->mailer->send($message)) {
            return $this->renderer->jsonApiRender($response, 204);
        };

        throw new JsonException($args['entity'], 400, 'Bad request', 'Bad request');
    }

    /**
     * @api {post} /user/reset-password Сброс пароля
     * @apiName ResetPassword
     * @apiGroup User
     *
     * @apiDescription Метод для изменения пароля.
     *
     * Вместе с паролем нужно отправить токен, который был отправлен пользователю на почту.
     *
     * @apiParam {String} email Email пользователя
     *
     * @apiParamExample {json} Пример запроса:
     *    {
     *      "data":{
     *        "attributes":{
     *          "token": "f35v3g7h3frw24yi58cawo2e2kqhy3i5_1466085622",
     *          "password": "qwerty"
     *        }
     *      }
     *    }
     *
     * @apiSuccessExample {json} Успешно (204)
     *     HTTP/1.1 204 OK
     *
     * @apiUse StandardErrors
     */
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionResetPassword(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();

        if (!isset($params['data']['attributes'])) {
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $rules = [
            'token'    => 'required',
            'password' => 'required',
        ];

        $validator = $this->validation->make($params['data']['attributes'], $rules);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $user = User::findByPasswordResetToken($params['data']['attributes']['token']);

        if ($user) {
            $user->setPassword($params['data']['attributes']['password']);
            $user->removePasswordResetToken();

            if($user->save()){
                return $this->renderer->jsonApiRender($response, 204);
            };
        }

        throw new JsonException($args['entity'], 400, 'Bad request', 'Bad request');
    }
}