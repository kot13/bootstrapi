<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /<resourceType> List of <resourceType>
 * @apiName Get<resourceTypeInCamelCase>s
 * @apiGroup <resourceTypeInCamelCase>
 *
 * @apiDescription Get list of <resourceType>
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *         {
 *           "type": "<resourceType>",
 *           "id": "1",
 *           "attributes": {
<attributes>
 *           },
 *           "links": {
 *             "self": "/<resourceType>/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 */

/**
 * @api {get} /<resourceType>/:id Get <resourceType>
 * @apiName Get<resourceTypeInCamelCase>
 * @apiGroup <resourceTypeInCamelCase>
 *
 * @apiDescription Get <resourceType>.
 *
 * @apiParam {Number} id Id <resourceType>
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "<resourceType>",
 *         "id": "1",
 *         "attributes": {
<attributes>
 *         },
 *         "links": {
 *           "self": "/<resourceType>/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

/**
 * @api {post} /<resourceType> Create <resourceType>
 * @apiName Create<resourceTypeInCamelCase>
 * @apiGroup <resourceTypeInCamelCase>
 *
 * @apiDescription Create <resourceType>.
 *
<params>
 *
 * @apiParamExample {json} Example request:
 *    {
 *      "data": {
 *        "attributes": {
<attributes>
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "<resourceType>",
 *         "id": "1",
 *         "attributes": {
<attributes>
 *         },
 *         "links": {
 *           "self": "/<resourceType>/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 */

/**
 * @api {patch} /<resourceType>/:id Update <resourceType>
 * @apiName Update<resourceTypeInCamelCase>
 * @apiGroup <resourceTypeInCamelCase>
 *
 * @apiDescription Update <resourceType>.
 *
<params>
 *
 * @apiParamExample {json} Example request:
 *    {
 *      "data": {
 *        "attributes": {
<attributes>
 *        }
 *      }
 *    }
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "<resourceType>",
 *         "id": "1",
 *         "attributes": {
<attributes>
 *         },
 *         "links": {
 *           "self": "/<resourceType>/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

/**
 * @api {delete} /<resourceType>/:id Delete <resourceType>
 * @apiName Delete<resourceTypeInCamelCase>
 * @apiGroup <resourceTypeInCamelCase>
 *
 * @apiDescription Delete <resourceType>.
 *
 * @apiParam {Number} id Id <resourceType>
 *
 * @apiSuccessExample {json} Success (204)
 *     HTTP/1.1 204 OK
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

final class <class> extends BaseSchema
{
    protected $resourceType = '<resourceType>';

    public function getId($entity)
    {
        return $entity->id;
    }

    public function getAttributes($entity)
    {
        return [
<attributesToClass>
        ];
    }
}
