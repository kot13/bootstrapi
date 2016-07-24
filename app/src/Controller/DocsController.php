<?php
namespace App\Controller;

class DocsController {

    public $view;

    public function __construct($container){
        $this->view = $container['view'];
    }

    public function actionIndex($request, $response, $args){
        $this->view->render($response, 'docs.twig');
        return $response;
    }
}