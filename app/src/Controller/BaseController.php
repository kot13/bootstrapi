<?php
namespace App\Controller;

use App\Observers;
use App\Model;

abstract class BaseController {
    public $validation;
    public $renderer;
    public $settings;
    public $mailer;
    public $user;

    /**
     * BaseController constructor.
     *
     * @param $container
     */
    public function __construct($container){
        $this->renderer   = $container['renderer'];
        $this->validation = $container['validation'];
        $this->settings   = $container['settings'];
        $this->mailer     = $container['mailer'];

        $this->registerModelObservers();
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