<?php
namespace App\Schema;

    /**
     * @api {get} /right Список прав
     * @apiName GetRights
     * @apiGroup Right
     *
     * @apiDescription Метод для получения списка прав.
     *
     * @apiHeader {String} Authorization Токен.
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
     *           "links": {
     *             "self": "http://skeleton.dev/api/right/1"
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
     * @apiParam {Number} id Id права
     *
     * @apiHeader {String} Authorization Токен.
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
     *         "links": {
     *           "self": "http://skeleton.dev/api/right/1"
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
     * @apiHeader {String} Authorization Токен.
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
     *         "links": {
     *           "self": "http://skeleton.dev/api/right/1"
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
     * @apiHeader {String} Authorization Токен.
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
     *           "updated_by": 1
     *         },
     *         "links": {
     *           "self": "http://skeleton.dev/api/right/1"
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
     * @apiParam {Number} id Id права
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

class RightSchema extends BaseSchema{

    protected $resourceType = 'right';

    public function getId($right)
    {
        return $right->id;
    }

    public function getAttributes($right)
    {
        return [
            'name' => $right->name,
            'description' => $right->description,
            'created_at' => $right->created_at,
            'updated_at' => $right->updated_at,
            'created_by' => $right->created_by,
            'updated_by' => $right->updated_by,
        ];
    }
}