<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /user Список пользователей
 * @apiName GetUsers
 * @apiGroup User
 *
 * @apiDescription Метод для получения списка пользователей.
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
 *           "type": "user",
 *           "id": "1",
 *           "attributes": {
 *             "full_name": "Тестовый пользователь",
 *             "email": "mail@example.com",
 *             "role_id": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "created_by": 0,
 *             "updated_by": null,
 *             "status": 1,
 *           },
 *           "relationships": {
 *             "role": {
 *               "data": {
 *                 "type": "role",
 *                 "id": "1"
 *               }
 *             }
 *           },
 *           "links": {
 *             "self": "http://bootstrapi.dev/api/user/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {get} /user/:id?include=role&fields[role]=name Получить пользователя
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiDescription Метод для получения пользователя.
 *
 * @apiPermission user
 *
 * @apiParam {Number} id Id пользователя
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "user",
 *         "id": "1",
 *         "attributes": {
 *           "full_name": "Тестовый пользователь",
 *           "email": "mail@example.com",
 *           "role_id": 1,
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 0,
 *           "updated_by": null,
 *           "status": 1,
 *         },
 *         "relationships": {
 *           "role": {
 *             "data": {
 *               "type": "role",
 *               "id": "1"
 *             }
 *           }
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/user/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

/**
 * @api {delete} /user/:id Удаление пользователя
 * @apiName DeleteUser
 * @apiGroup User
 *
 * @apiDescription Метод для удаления пользователя.
 *
 * @apiPermission admin
 *
 * @apiParam {Number} id Id пользователя
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

/**
 * @api {post} /user Создание пользователя
 * @apiName CreateUser
 * @apiGroup User
 *
 * @apiDescription Метод для создания нового пользователя.
 *
 * @apiPermission admin
 *
 * @apiParam {String} full_name Полное имя пользователя
 * @apiParam {String} email Email пользователя (уникальный)
 * @apiParam {String} password Пароль
 * @apiParam {Number} role_id Id роли пользователя
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data":{
 *        "attributes":{
 *          "full_name":"Тестовый пользователь",
 *          "email": "mail@example.com",
 *          "password": "qwerty",
 *          "role_id": 1,
 *          "status": 1
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
 *         "type": "user",
 *         "id": "2",
 *         "attributes": {
 *           "full_name": "Тестовый пользователь",
 *           "email": "mail2@example.com",
 *           "role_id": 1,
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 1,
 *           "updated_by": null,
 *           "status": 1,
 *         },
 *         "relationships": {
 *            "role": {
 *             "data": {
 *               "type": "role",
 *               "id": "1"
 *             }
 *           }
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/user/2"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {patch / put} /user/:id Изменение пользователя
 * @apiName UpdateUser
 * @apiGroup User
 *
 * @apiDescription Метод для изменения пользователя.
 *
 *
 * @apiPermission admin
 *
 * @apiParam {String} full_name Полное имя пользователя
 * @apiParam {String} email Email пользователя (уникальный)
 * @apiParam {String} password Пароль
 * @apiParam {Number} role_id Id роли пользователя
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data":{
 *        "attributes":{
 *          "full_name":"Тестовый пользователь",
 *          "email": "mail@example.com",
 *          "password": "qwerty",
 *          "role_id": 1
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "user",
 *         "id": 2,
 *         "attributes": {
 *           "full_name": "Тестовый пользователь",
 *           "email": "mail1@example.com",
 *           "role_id": 1,
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "updated_at": "2016-10-17T07:38:21+0000",
 *           "created_by": null,
 *           "updated_by": null,
 *           "status": 1,
 *         },
 *         "relationships": {
 *           "role": {
 *             "data": {
 *               "type": "role",
 *               "id": "1"
 *             }
 *           }
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/user/2"
 *         }
 *       }
 *     }
 *
 * @apiHeader {String} Authorization Bearer TOKEN
 * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

/**
 * @api {post} /user/request-password-reset Запрос на сброс пароля
 * @apiName RequestPasswordReset
 * @apiGroup User
 *
 * @apiDescription Метод высылающий на email пользователя письмо со ссылкой для изменения пароля.
 *
 * В ссылке отправляется токен для сброса пароля. Его нужно отправить в методе /user/password-reset
 *
 * @apiParam {String} email Email пользователя
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data":{
 *        "attributes":{
 *          "email": "mail@example.com"
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Успешно (204)
 *     HTTP/1.1 204 OK
 *
 * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
 *
 * @apiUse StandardErrors
 */

/**
 * @api {post} /user/password-reset Сброс пароля
 * @apiName PasswordReset
 * @apiGroup User
 *
 * @apiDescription Метод для изменения пароля.
 *
 * Вместе с паролем нужно отправить токен, который был отправлен пользователю на почту.
 *
 * @apiParam {String} email Email пользователя
 *
 * @apiParamExample {json} Пример запроса:
 *    {
 *      "data":{
 *        "attributes":{
 *          "token": "f35v3g7h3frw24yi58cawo2e2kqhy3i5_1466085622",
 *          "password": "qwerty"
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Успешно (204)
 *     HTTP/1.1 204 OK
 *
 * @apiHeader {String} Content-Type application/vnd.api+json <br/> application/json
 *
 * @apiUse StandardErrors
 */

final class UserSchema extends BaseSchema
{
    protected $resourceType = 'user';

    public function getId($user)
    {
        return $user->id;
    }

    public function getAttributes($user)
    {
        return [
            'full_name'  => $user->full_name,
            'email'      => $user->email,
            'role_id'    => (int)$user->role_id,
            'created_at' => Carbon::parse($user->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'updated_at' => Carbon::parse($user->updated_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'created_by' => $user->created_by,
            'updated_by' => $user->updated_by,
            'status'     => $user->status,
        ];
    }

    public function getRelationships($user, $isPrimary, array $includeList)
    {
        return [
            'role' => [
                self::DATA => $user->role,
            ],
        ];
    }
}
