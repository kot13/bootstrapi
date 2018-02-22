<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /user List of user
 * @apiName GetUsers
 * @apiGroup User
 *
 * @apiDescription Get list of user
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *         {
 *           "type": "user",
 *           "id": "1",
 *           "attributes": {
 *             "email": "String",
 *             "full_name": "String",
 *             "password": "String",
 *             "password_reset_token": "String",
 *             "role_id": 1,
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "deleted_at": "2016-10-17T07:38:21+0000",
 *             "status": 1
 *           },
 *           "links": {
 *             "self": "/user/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 */

/**
 * @api {get} /user/:id Get user
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiDescription Get user.
 *
 * @apiParam {Number} id Id user
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "user",
 *         "id": "1",
 *         "attributes": {
 *             "email": "String",
 *             "full_name": "String",
 *             "password": "String",
 *             "password_reset_token": "String",
 *             "role_id": 1,
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "deleted_at": "2016-10-17T07:38:21+0000",
 *             "status": 1
 *         },
 *         "links": {
 *           "self": "/user/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

/**
 * @api {post} /user Create user
 * @apiName CreateUser
 * @apiGroup User
 *
 * @apiDescription Create user.
 *
 * @apiParam {String} email
 * @apiParam {String} full_name
 * @apiParam {String} password
 * @apiParam {String} password_reset_token
 * @apiParam {Integer} role_id
 * @apiParam {Integer} created_by
 * @apiParam {Integer} updated_by
 * @apiParam {Datetime} created_at
 * @apiParam {Datetime} updated_at
 * @apiParam {Datetime} deleted_at
 * @apiParam {Integer} status
 *
 * @apiParamExample {json} Example request:
 *    {
 *      "data": {
 *        "attributes": {
 *             "email": "String",
 *             "full_name": "String",
 *             "password": "String",
 *             "password_reset_token": "String",
 *             "role_id": 1,
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "deleted_at": "2016-10-17T07:38:21+0000",
 *             "status": 1
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "user",
 *         "id": "1",
 *         "attributes": {
 *             "email": "String",
 *             "full_name": "String",
 *             "password": "String",
 *             "password_reset_token": "String",
 *             "role_id": 1,
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "deleted_at": "2016-10-17T07:38:21+0000",
 *             "status": 1
 *         },
 *         "links": {
 *           "self": "/user/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 */

/**
 * @api {patch} /user/:id Update user
 * @apiName UpdateUser
 * @apiGroup User
 *
 * @apiDescription Update user.
 *
 * @apiParam {String} email
 * @apiParam {String} full_name
 * @apiParam {String} password
 * @apiParam {String} password_reset_token
 * @apiParam {Integer} role_id
 * @apiParam {Integer} created_by
 * @apiParam {Integer} updated_by
 * @apiParam {Datetime} created_at
 * @apiParam {Datetime} updated_at
 * @apiParam {Datetime} deleted_at
 * @apiParam {Integer} status
 *
 * @apiParamExample {json} Example request:
 *    {
 *      "data": {
 *        "attributes": {
 *             "email": "String",
 *             "full_name": "String",
 *             "password": "String",
 *             "password_reset_token": "String",
 *             "role_id": 1,
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "deleted_at": "2016-10-17T07:38:21+0000",
 *             "status": 1
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "user",
 *         "id": "1",
 *         "attributes": {
 *             "email": "String",
 *             "full_name": "String",
 *             "password": "String",
 *             "password_reset_token": "String",
 *             "role_id": 1,
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000",
 *             "deleted_at": "2016-10-17T07:38:21+0000",
 *             "status": 1
 *         },
 *         "links": {
 *           "self": "/user/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

/**
 * @api {delete} /user/:id Delete user
 * @apiName DeleteUser
 * @apiGroup User
 *
 * @apiDescription Delete user.
 *
 * @apiParam {Number} id Id user
 *
 * @apiSuccessExample {json} Success (204)
 *     HTTP/1.1 204 OK
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

final class UserSchema extends BaseSchema
{
    protected $resourceType = 'user';

    public function getId($entity)
    {
        return $entity->id;
    }

    public function getAttributes($entity)
    {
        return [
			'email'	=> (string)$entity->email,
			'full_name'	=> (string)$entity->full_name,
			'password'	=> (string)$entity->password,
			'password_reset_token'	=> (string)$entity->password_reset_token,
			'role_id'	=> (integer)$entity->role_id,
			'created_by'	=> (integer)$entity->created_by,
			'updated_by'	=> (integer)$entity->updated_by,
			'created_at'	=> Carbon::parse($entity->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
			'updated_at'	=> Carbon::parse($entity->updated_at)->setTimezone('UTC')->format(Carbon::ISO8601),
			'deleted_at'	=> Carbon::parse($entity->deleted_at)->setTimezone('UTC')->format(Carbon::ISO8601),
			'status'	=> (integer)$entity->status,
        ];
    }
}
