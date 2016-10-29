<?php
namespace App\Schema;

    /**
     * @api {get} /role Список ролей
     * @apiName GetRoles
     * @apiGroup Role
     *
     * @apiDescription Метод для получения списка ролей.
     *
     * @apiHeader {String} Authorization Токен.
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
     *             "created_at": {
     *               "date": "2016-10-13 21:37:40.000000",
     *               "timezone_type": 3,
     *               "timezone": "Europe/Moscow"
     *             },
     *             "updated_at": {
     *               "date": "2016-10-13 21:37:40.000000",
     *               "timezone_type": 3,
     *               "timezone": "Europe/Moscow"
     *             },
     *             "created_by": 0,
     *             "updated_by": null
     *           },
     *           "relationships": {
     *             "rights": {
     *               "data": []
     *             }
     *           },
     *           "links": {
     *             "self": "http://skeleton.dev/api/role/1"
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
     * @apiParam {Number} id Id роли
     *
     * @apiHeader {String} Authorization Токен.
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
     *           "created_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "updated_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "created_by": 0,
     *           "updated_by": null
     *         },
     *         "relationships": {
     *           "rights": {
     *             "data": []
     *           }
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/role/1"
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
     * @apiHeader {String} Authorization Токен.
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
     *           "created_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "updated_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "created_by": 1,
     *           "updated_by": null
     *         },
     *         "relationships": {
     *            "rights": {
     *             "data": []
     *           }
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/role/2"
     *         }
     *       }
     *     }
     *
     * @apiUse StandardErrors
     * @apiUse UnauthorizedError
     */

    /**
     * @api {patch} /role/:id Изменение роли
     * @apiName UpdateRole
     * @apiGroup Role
     *
     * @apiDescription Метод для изменения роли.
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
     * @apiHeader {String} Authorization Токен.
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
     *           "created_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "updated_at": {
     *             "date": "2016-10-13 21:37:40.000000",
     *             "timezone_type": 3,
     *             "timezone": "Europe/Moscow"
     *           },
     *           "created_by": 1,
     *           "updated_by": null
     *         },
     *         "relationships": {
     *            "rights": {
     *             "data": []
     *           }
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/role/2"
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
     * @apiParam {Number} id Id роли
     *
     * @apiHeader {String} Authorization Токен.
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
            'created_at'  => $role->created_at,
            'updated_at'  => $role->updated_at,
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