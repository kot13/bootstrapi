<?php

namespace App\Controller;

use App\Observers;
use App\Model;
use App\Common\JsonException;
use App\Requests\IRequest;

use Slim\Container;

/**
 * Class BaseController
 *
 * @package App\Controller
 */
abstract class BaseController
{
    /**
     * @var \Illuminate\Validation\Factory
     */
    public $validation;

    /**
     * @var \App\Common\JsonApiEncoder
     */
    public $encoder;

    /**
     * @var \App\Common\ApiRenderer
     */
    public $apiRenderer;

    /**
     * @var array
     */
    public $settings;

    /**
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * @var \App\Common\MailRenderer
     */
    public $mailRenderer;

    /**
     * BaseController constructor.
     *
     * @param \Slim\Container $container
     */
    public function __construct(Container $container)
    {
        $this->settings     = $container['settings'];

        // register providers
        $this->apiRenderer  = $container['apiRenderer'];
        $this->validation   = $container['validation'];
        $this->mailer       = $container['mailer'];
        $this->mailRenderer = $container['mailRenderer'];
        $this->encoder      = $container['encoder'];

        $this->registerModelObservers();
    }

    /**
     * @param array|null|object $params
     * @param string            $entity
     * @param IRequest          $request
     *
     * @return bool
     * @throws JsonException
     */
    public function validateRequestParams($params, $entity, $request)
    {
        if (!isset($params['data']['attributes'])) {
            throw new JsonException($entity, 400, 'Invalid Attribute', 'Do not see required attribute - data.');
        }

        $validator = $this->validation->make($params['data']['attributes'], $request->rules());

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());
            throw new JsonException($entity, 400, 'Invalid Attribute', $messages);
        }

        return true;
    }

    /**
     * Register model observers
     */
    private function registerModelObservers()
    {
        $observers = [
            Observers\CreatedByAndUpdatedByObserver::class => [
                Model\Right::class,
                Model\Role::class,
                Model\User::class,
            ],

            Observers\LoggerObserver::class => [
                Model\Right::class,
                Model\Role::class,
                Model\User::class,
            ]
        ];

        foreach ($observers as $observer => $models) {
            foreach ($models as $model) {
                call_user_func($model. '::observe', $observer);
            }
        }
    }
}
