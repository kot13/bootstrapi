<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /role Список ролей
 * @apiName GetRoles
 * @apiGroup Role
 *
 * @apiDescription Метод для получения списка ролей.
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
 *           "type": "role",
 *           "id": "1",
 *           "attributes": {
 *             "name": "admin",
 *             "description": "Администратор",
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "created_by": 0,
 *             "updated_by": null
 *           },
 *           "relationships": {
 *             "rights": {
 *               "data": []
 *             }
 *           },
 *           "links": {
 *             "self": "http://bootstrapi.dev/api/role/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {get} /role/:id?include=rights&fields[right]=name Получить роль
 * @apiName GetRole
 * @apiGroup Role
 *
 * @apiDescription Метод для получения роли.
 *
 * @apiPermission user
 *
 * @apiParam {Number} id Id роли
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "role",
 *         "id": "1",
 *         "attributes": {
 *           "name": "admin",
 *           "description": "Администратор",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 0,
 *           "updated_by": null
 *         },
 *         "relationships": {
 *           "rights": {
 *             "data": []
 *           }
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/role/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

/**
 * @api {post} /role Создание роли
 * @apiName CreateRole
 * @apiGroup Role
 *
 * @apiDescription Метод для создания новой роли.
 *
 * @apiPermission admin
 *
 * @apiParam {String} name Имя роли (уникальный)
 * @apiParam {String} description Человекопонятное описание
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data":{
 *        "attributes":{
 *          "name":"guest",
 *          "description": "Гость"
 *        }
 *      }
 *    }
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "role",
 *         "id": "2",
 *         "attributes": {
 *           "name": "guest",
 *           "description": "Гость",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 1,
 *           "updated_by": null
 *         },
 *         "relationships": {
 *            "rights": {
 *             "data": []
 *           }
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/role/2"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {patch / put} /role/:id Изменение роли
 * @apiName UpdateRole
 * @apiGroup Role
 *
 * @apiDescription Метод для изменения роли.
 *
 * @apiPermission admin
 *
 * @apiParam {String} name Имя роли (уникальный)
 * @apiParam {String} description Человекопонятное описание
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data":{
 *        "attributes":{
 *          "name":"guest",
 *          "description": "Гость"
 *        }
 *      }
 *    }
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "role",
 *         "id": "2",
 *         "attributes": {
 *           "name": "guest",
 *           "description": "Гость",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 1,
 *           "updated_by": null
 *         },
 *         "relationships": {
 *            "rights": {
 *             "data": []
 *           }
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/role/2"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

/**
 * @api {delete} /role/:id Удаление роли
 * @apiName DeleteRole
 * @apiGroup Role
 *
 * @apiDescription Метод для удаления роли.
 *
 * @apiPermission admin
 *
 * @apiParam {Number} id Id роли
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
final class RoleSchema extends BaseSchema
{
    protected $resourceType = 'role';

    public function getId($role)
    {
        return $role->id;
    }

    public function getAttributes($role)
    {
        return [
            'name'        => $role->name,
            'description' => $role->description,
            'created_at'  => Carbon::parse($role->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'updated_at'  => Carbon::parse($role->updated_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'created_by'  => $role->created_by,
            'updated_by'  => $role->updated_by,
        ];
    }

    public function getRelationships($role, $isPrimary, array $includeList)
    {
        return [
            'rights' => [
                self::DATA => $role->rights,
            ],
        ];
    }
}
