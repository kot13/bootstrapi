<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /right Список прав
 * @apiName GetRights
 * @apiGroup Right
 *
 * @apiDescription Метод для получения списка прав.
 *
 * @apiPermission user
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *         {
 *           "type": "right",
 *           "id": "1",
 *           "attributes": {
 *             "name": "manageUsers",
 *             "description": "Управление пользователями",
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "created_by": 0,
 *             "updated_by": null
 *           },
 *           "links": {
 *             "self": "http://bootstrapi.dev/api/right/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {get} /right/:id Получить право
 * @apiName GetRight
 * @apiGroup Right
 *
 * @apiDescription Метод для получения права.
 *
 * @apiPermission user
 *
 * @apiParam {Number} id Id права
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "right",
 *         "id": "1",
 *         "attributes": {
 *           "name": "manageUsers",
 *           "description": "Управление пользователями",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 0,
 *           "updated_by": null
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/right/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

/**
 * @api {post} /right Создание права
 * @apiName CreateRight
 * @apiGroup Right
 *
 * @apiDescription Метод для создания нового права.
 *
 * @apiPermission admin
 *
 * @apiParam {String} name Имя права (уникальный)
 * @apiParam {String} description Человекопонятное описание
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data": {
 *        "attributes": {
 *          "name": "manageUsers",
 *          "description": "Управление пользователями"
 *        }
 *      }
 *    }
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "right",
 *         "id": "1",
 *         "attributes": {
 *           "name": "manageUsers",
 *           "description": "Управление пользователями",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 1,
 *           "updated_by": null
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/right/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {patch} /right/:id Изменение права
 * @apiName UpdateRight
 * @apiGroup Right
 *
 * @apiDescription Метод для изменения права.
 *
 * @apiPermission admin
 *
 * @apiParam {String} name Имя права (уникальный)
 * @apiParam {String} description Человекопонятное описание
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data": {
 *        "attributes": {
 *          "name": "manageUsers",
 *          "description": "Управление пользователями"
 *        }
 *      }
 *    }
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "right",
 *         "id": "1",
 *         "attributes": {
 *           "name": "manageUsers",
 *           "description": "Управление пользователями",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 1,
 *           "updated_by": 1
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/right/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

/**
 * @api {delete} /right/:id Удаление права
 * @apiName DeleteRight
 * @apiGroup Right
 *
 * @apiDescription Метод для удаления права.
 *
 * @apiPermission admin
 *
 * @apiParam {Number} id Id права
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (204)
 *     HTTP/1.1 204 OK
 *
 * @apiUse UnauthorizedError
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

final class RightSchema extends BaseSchema
{
    protected $resourceType = 'right';

    public function getId($right)
    {
        return $right->id;
    }

    public function getAttributes($right)
    {
        return [
            'name'        => $right->name,
            'description' => $right->description,
            'created_at'  => Carbon::parse($right->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'updated_at'  => Carbon::parse($right->updated_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'created_by'  => $right->created_by,
            'updated_by'  => $right->updated_by,
        ];
    }
}
