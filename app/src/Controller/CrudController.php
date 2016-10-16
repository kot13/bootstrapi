<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Factories\Factory;
use \Neomerx\JsonApi\Document\Error;

use App\Common\Helper;

final class CrudController extends BaseController{

    public function actionIndex($request, $response, $args){
        $factory    = new Factory();
        $parameters = $factory->createQueryParametersParser()->parse($request);

        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
        $params = $request->getQueryParams();

        if(isset($params['withTrashed']) && $params['withTrashed'] == 1){
            $query = $modelName::withTrashed();
        }

        if(isset($params['filter']) && count($params['filter']) > 0){
            foreach($params['filter'] as $key => $values){
                if(isset($query)) {
                    $query = $query->whereIn($key, explode(',', $values));
                } else {
                    $query = $modelName::whereIn($key, explode(',', $values));
                }
            }
        }

        if(isset($query)) {
            $entities = $query->get();
        } else {
            $entities = $modelName::all();
        }

        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result = $encoder->encodeData($entities, $parameters);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    public function actionGet($request, $response, $args){
        $factory    = new Factory();
        $parameters = $factory->createQueryParametersParser()->parse($request);

        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
        $params = $request->getQueryParams();

        if(isset($params['expand']) && $params['expand'] == 1 && count($expandEntity) > 0) {
            $entity = $modelName::with(array_keys($expandEntity))->where('id', $args['id'])->get();
        } else {
            $entity = $modelName::find($args['id']);
        }

        if (!$entity) {
            $error = new Error(
                $args['entity'],
                null,
                '404',
                '404',
                'Not found',
                'Entity not found'
            );

            $result = Encoder::instance()->encodeError($error);

            return $this->renderer->jsonApiRender($response, 404, $result);
        }

        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result = $encoder->encodeData($entity, $parameters);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    public function actionCreate($request, $response, $args){
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
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

        $validator = $this->validation->make($params['data']['attributes'], $modelName::$rules);

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

        $entity = $modelName::create($params['data']['attributes']);

        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));

        $result = $encoder->encodeData($entity);

        return $this->renderer->jsonApiRender($response, 200, $result);

    }

    public function actionUpdate($request, $response, $args){
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
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

        $entity = $modelName::find($args['id']);

        if (!$entity) {
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

        $validator = $this->validation->make($params['data']['attributes'], $modelName::$rules);

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

        $entity->update($params['data']['attributes']);

        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));

        $result = $encoder->encodeData($entity);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    public function actionDelete($request, $response, $args){
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $entity = $modelName::find($args['id']);

        if (!$entity) {
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

        $entity->delete();

        return $this->renderer->jsonApiRender($response, 204);
    }

}