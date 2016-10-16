<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Document\Error;

use App\Model\User;

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
    public function actionCreate($request, $response, $args){
        $expandEntity = User::$expand;
        $params = $request->getParsedBody();

        if(!isset($params['data']['attributes'])){
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                'Not required attributes - data.'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $validator = $this->validation->make($params['data']['attributes'], User::$rules['create']);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                $messages
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $exist = User::exist($params['data']['attributes']['email']);

        if ($exist) {
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'User already exists',
                'User already exists'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $user = User::create($params['data']['attributes']);
        $user->setPassword($params['data']['attributes']['password']);
        $user->save();

        $encodeEntities = [User::class => User::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));

        $result = $encoder->encodeData($user);

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
    public function actionUpdate($request, $response, $args){
        $expandEntity = User::$expand;
        $params = $request->getParsedBody();

        if(!isset($params['data']['attributes'])){
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                'Not required attributes - data.'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $user = User::find($args['id']);

        if (!$user) {
            $error = new Error(
                $args['entity'],
                null,
                '404',
                '404',
                'Not found',
                'Entities not found'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 404, $result);
        }

        $validator = $this->validation->make($params['data']['attributes'], User::$rules['update']);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                $messages
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $user->update($params['data']['attributes']);

        if(isset($params['data']['attributes']['password'])) {
            $user->setPassword($params['data']['attributes']['password']);
            $user->save();
        }

        $encodeEntities = [User::class => User::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));

        $result = $encoder->encodeData($user);

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
    public function actionRequestResetPassword($request, $response, $args){
        $params = $request->getParsedBody();

        if(!isset($params['data']['attributes'])){
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                'Not required attributes - data.'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $validator = $this->validation->make($params['data']['attributes'], ['email' => 'required|email']);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                $messages
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $user = User::findUserByEmail($params['data']['attributes']['email']);

        if (!$user) {
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Bad request',
                'Bad request'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }

        if (!$user->save()) {
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Bad request',
                'Bad request'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        // TODO вынести отправку в абстракцию, шаблонизация письма

        $emailText = '<a href="'.$this->settings['params']['host'].'/reset-password?reset_token='.$user->password_reset_token.'">Ссылка для восстановления пароля</a>';

        $message = \Swift_Message::newInstance('Восстановление пароля для доступа в example.com')
            ->setFrom(['no-reply@example.com' => 'Почтовик example.com'])
            ->setTo([$user->email => $user->full_name])
            ->setBody(
                '<html>' .
                ' <head></head>' .
                ' <body>' .
                $emailText.
                ' </body>' .
                '</html>',
                'text/html'
            );

        if ($this->mailer->send($message)){
            return $this->renderer->jsonApiRender($response, 204);
        };

        $error = new Error(
            $args['entity'],
            null,
            '400',
            '400',
            'Bad request',
            'Bad request'
        );

        $result = Encoder::instance()->encodeError($error);

        return $this->renderer->jsonApiRender($response, 400, $result);
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
    public function actionResetPassword($request, $response, $args){
        $params = $request->getParsedBody();

        if(!isset($params['data']['attributes'])){
            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                'Not required attributes - data.'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $rules = [
            'token' => 'required',
            'password' => 'required',
        ];

        $validator = $this->validation->make($params['data']['attributes'], $rules);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            $error = new Error(
                $args['entity'],
                null,
                '400',
                '400',
                'Invalid Attribute',
                $messages
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 400, $result);
        }

        $user = User::findByPasswordResetToken($params['data']['attributes']['token']);

        if($user){
            $user->setPassword($params['data']['attributes']['password']);
            $user->removePasswordResetToken();

            if($user->save()){
                return $this->renderer->jsonApiRender($response, 204);
            };
        }

        $error = new Error(
            $args['entity'],
            null,
            '400',
            '400',
            'Bad request',
            'Bad request'
        );

        $result = Encoder::instance()->encodeError($error);

        return $this->renderer->jsonApiRender($response, 400, $result);
    }
}