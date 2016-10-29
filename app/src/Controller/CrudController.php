<?php
namespace App\Controller;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Factories\Factory;

use App\Common\Helper;
use App\Common\JsonException;

use Slim\Http\Request;
use Slim\Http\Response;

final class CrudController extends BaseController
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     */
    public function actionIndex(Request $request, Response $response, $args)
    {
        $factory    = new Factory();
        $parameters = $factory->createQueryParametersParser()->parse($request);

        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
        $params = $request->getQueryParams();

        if (isset($params['withTrashed']) && $params['withTrashed'] == 1) {
            $query = $modelName::withTrashed();
        }

        if (isset($params['filter']) && count($params['filter']) > 0) {
            foreach($params['filter'] as $key => $values){
                if(isset($query)) {
                    $query = $query->whereIn($key, explode(',', $values));
                } else {
                    $query = $modelName::whereIn($key, explode(',', $values));
                }
            }
        }

        if (isset($query)) {
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

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionGet(Request $request, Response $response, $args)
    {
        $factory      = new Factory();
        $parameters   = $factory->createQueryParametersParser()->parse($request);
        $modelName    = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
        $params       = $request->getQueryParams();

        if (isset($params['expand']) && $params['expand'] == 1 && count($expandEntity) > 0) {
            $entity = $modelName::with(array_keys($expandEntity))->where('id', $args['id'])->get();
        } else {
            $entity = $modelName::find($args['id']);
        }

        if (!$entity) {
            throw new JsonException($args['entity'], 404, 'Not found','Entity not found');
        }

        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result  = $encoder->encodeData($entity, $parameters);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionCreate(Request $request, Response $response, $args)
    {
        $modelName    = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
        $params       = $request->getParsedBody();

        if (!isset($params['data']['attributes'])) {
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $validator = $this->validation->make($params['data']['attributes'], $modelName::$rules);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $entity         = $modelName::create($params['data']['attributes']);
        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result  = $encoder->encodeData($entity);

        return $this->renderer->jsonApiRender($response, 200, $result);

    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionUpdate(Request $request, Response $response, $args)
    {
        $modelName    = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $expandEntity = $modelName::$expand;
        $params       = $request->getParsedBody();

        if(!isset($params['data']['attributes'])){
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $entity = $modelName::find($args['id']);

        if (!$entity) {
            throw new JsonException($args['entity'], 404, 'Not found','Entity not found');
        }

        $validator = $this->validation->make($params['data']['attributes'], $modelName::$rules);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $entity->update($params['data']['attributes']);

        $encodeEntities = [$modelName => $modelName::$schemaName];

        foreach ($expandEntity as $name => $className){
            $encodeEntities[$className] = $className::$schemaName;
        }

        $encoder = Encoder::instance($encodeEntities, new EncoderOptions(JSON_PRETTY_PRINT, $this->settings['params']['host'].'/api'));
        $result  = $encoder->encodeData($entity);

        return $this->renderer->jsonApiRender($response, 200, $result);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws JsonException
     */
    public function actionDelete(Request $request, Response $response, $args)
    {
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $entity    = $modelName::find($args['id']);

        if (!$entity) {
            throw new JsonException($args['entity'], 404, 'Not found','Entity not found');
        }

        $entity->delete();

        return $this->renderer->jsonApiRender($response, 204);
    }

}