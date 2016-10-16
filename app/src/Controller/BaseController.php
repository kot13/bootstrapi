<?php
namespace App\Controller;

abstract class BaseController {
    public $validation;
    public $renderer;
    public $settings;
    public $mailer;

    public function __construct($container){
        $this->renderer   = $container['renderer'];
        $this->validation = $container['validation'];
        $this->settings   = $container['settings'];
        $this->mailer     = $container['mailer'];
    }
}