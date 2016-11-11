<?php
namespace App\Controller;

use App\Observers;
use App\Model;
use App\Common\JsonException;
use App\Common\Auth;

use App\Requests\IRequest;
use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Factories\Factory;

use Slim\Http\Request;

abstract class BaseController
{
    /**
     * @var \Illuminate\Validation\Factory;
     */
    public $validation;

    /**
     * @var \App\Common\Renderer
     */
    public $renderer;

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
     * @var array
     */
    private $encodeEntitiesExtended = [
        'App\Model\Log'   => 'App\Schema\LogSchema',
        'App\Model\Right' => 'App\Schema\RightSchema',
        'App\Model\Role'  => 'App\Schema\RoleSchema',
        'App\Model\User'  => 'App\Schema\UserSchemaExtended',
    ];

    /**
     * @var array
     */
    private $encodeEntities = [
        'App\Model\Log'   => 'App\Schema\LogSchema',
        'App\Model\Right' => 'App\Schema\RightSchema',
        'App\Model\Role'  => 'App\Schema\RoleSchema',
        'App\Model\User'  => 'App\Schema\UserSchema',
    ];

    /**
     * BaseController constructor.
     *
     * @param $container
     */
    public function __construct($container)
    {
        $this->renderer     = $container['renderer'];
        $this->validation   = $container['validation'];
        $this->settings     = $container['settings'];
        $this->mailer       = $container['mailer'];
        $this->mailRenderer = $container['mailRenderer'];

        $this->registerModelObservers();
    }

    /**
     * @param array    $params
     * @param string   $entity
     * @param IRequest $request
     *
     * @return bool
     * @throws JsonException
     */
    public function validationRequest($params, $entity, $request)
    {
        if (!isset($params['data']['attributes'])) {
            throw new JsonException($entity, 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $validator = $this->validation->make($params['data']['attributes'], $request->rules());

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());
            throw new JsonException($entity, 400, 'Invalid Attribute', $messages);
        }

        return true;
    }

    /**
     * @param Request $request
     * @param mixed   $entities
     *
     * @return string
     */
    public function encode(Request $request, $entities)
    {
        $factory        = new Factory();
        $parameters     = $factory->createQueryParametersParser()->parse($request);
        $user           = Auth::getUser();
        $encodeEntities = $this->encodeEntities;

        if ($user && $user->role_id == Model\User::ROLE_ADMIN) {
            $encodeEntities = $this->encodeEntitiesExtended;
        }

        $encoder    = Encoder::instance(
            $encodeEntities,
            new EncoderOptions(
                JSON_PRETTY_PRINT,
                $this->settings['params']['host'].'/api'
            )
        );

        return $encoder->encodeData($entities, $parameters);
    }

    /**
     * Register model observers
     */
    private function registerModelObservers(){
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

        foreach($observers as $observer => $models){
            foreach($models as $model){
                call_user_func($model. '::observe', $observer);
            }
        }
    }
}