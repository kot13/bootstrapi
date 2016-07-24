<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Document\Error;

use App\Model\User;

final class UserController extends BaseController
{
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