<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Document\Error;

use App\Model\User;

final class UserController extends BaseController
{
    /**
     * @api {post} /user Создание пользователя
     * @apiName CreateUser
     * @apiGroup User
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
     * @apiHeader {String} Authorization Токен.
     *
     * @apiSuccessExample {json} Успешно (200)
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "type": "",
     *         "id": ,
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
     *           "created_by": null,
     *           "updated_by": null,
     *           "status": null,
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
     *           "self": "http://skeleton.dev/api/user/4"
     *         }
     *       }
     *     }
     *
     * @apiSuccessExample {json} Не авторизован (401)
     *     HTTP/1.1 401 Unauthorized
     *     {
     *       "errors": [
     *         {
     *           "status": "401",
     *           "code": "401",
     *           "title": "Not authorized",
     *           "detail": "The user must be authorized"
     *         }
     *       ]
     *     }
     * @apiSuccessExample {json} Неверный запрос (400)
     *     HTTP/1.1 400 Unauthorized
     *     {
     *       "errors": [
     *         {
     *           "id": "user",
     *           "status": "400",
     *           "code": "400",
     *           "title": "Invalid Attribute",
     *           "detail": "Not required attributes - data."
     *         }
     *       ]
     *     }
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