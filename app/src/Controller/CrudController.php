<?php
namespace App\Controller;

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
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $params    = $request->getQueryParams();

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

        $result = $this->encode($request, $entities);

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
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $entity    = $modelName::find($args['id']);

        if (!$entity) {
            throw new JsonException($args['entity'], 404, 'Not found','Entity not found');
        }

        $result = $this->encode($request, $entity);

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
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $params    = $request->getParsedBody();

        if (!isset($params['data']['attributes'])) {
            throw new JsonException($args['entity'], 400, 'Invalid Attribute', 'Not required attributes - data.');
        }

        $validator = $this->validation->make($params['data']['attributes'], $modelName::$rules);

        if ($validator->fails()) {
            $messages = implode(' ', $validator->messages()->all());

            throw new JsonException($args['entity'], 400, 'Invalid Attribute', $messages);
        }

        $entity = $modelName::create($params['data']['attributes']);
        $result = $this->encode($request, $entity);

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
        $modelName = 'App\Model\\'.Helper::dashesToCamelCase($args['entity'], true);
        $params    = $request->getParsedBody();

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

        $result = $this->encode($request, $entity);

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