<?php
namespace App\Controller;

use App\Model\User;
use App\Common\JsonException;
use App\Requests\RequestPasswordResetRequest;
use App\Requests\PasswordResetRequest;
use App\Requests\UserCreateRequest;
use App\Requests\UserUpdateRequest;
use Slim\Http\Request;
use Slim\Http\Response;

final class UserController extends CrudController
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function actionCreate(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();

        $this->validationRequest($params, $args['entity'], new UserCreateRequest());

        $exist = User::exist($params['data']['attributes']['email']);

        if ($exist) {
            throw new JsonException($args['entity'], 400, 'User already exists', 'User already exists');
        }

        $user = new User($params['data']['attributes']);
        $user->setPassword($params['data']['attributes']['password']);
        $user->save();

        $result = $this->encoder->encode($request, $user);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function actionUpdate(Request $request, Response $response, $args)
    {
        $user = User::find($args['id']);

        if (!$user) {
            throw new JsonException($args['entity'], 404, 'Not found', 'Entity not found');
        }

        $params = $request->getParsedBody();

        $this->validationRequest($params, $args['entity'], new UserUpdateRequest());

        $user->update($params['data']['attributes']);

        if (isset($params['data']['attributes']['password'])) {
            $user->setPassword($params['data']['attributes']['password']);
            $user->save();
        }

        $result = $this->encoder->encode($request, $user);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function actionRequestPasswordReset(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();

        $this->validationRequest($params, $args['entity'], new RequestPasswordResetRequest());

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
            ->setBody($this->mailRenderer->render(
                '/RequestPasswordReset.php',
                [
                    'host'  => $this->settings['params']['host'],
                    'token' => $user->password_reset_token
                ]
            ), 'text/html');

        if ($this->mailer->send($message)) {
            return $this->renderer->jsonApiRender($response, 204);
        };

        throw new JsonException($args['entity'], 400, 'Bad request', 'Bad request');
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws JsonException
     */
    public function actionPasswordReset(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();

        $this->validationRequest($params, $args['entity'], new PasswordResetRequest());

        $user = User::findByPasswordResetToken($params['data']['attributes']['token']);

        if ($user) {
            $user->setPassword($params['data']['attributes']['password']);
            $user->removePasswordResetToken();

            if ($user->save()) {
                return $this->renderer->jsonApiRender($response, 204);
            };
        }

        throw new JsonException($args['entity'], 400, 'Bad request', 'Bad request');
    }
}
