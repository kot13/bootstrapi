<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /media-file List of media-file
 * @apiName GetMediaFiles
 * @apiGroup MediaFile
 *
 * @apiDescription Get list of media-file
 *
 * @apiPermission admin
 * @apiHeader {String} Authorization Bearer TOKEN
 * @apiUse UnauthorizedError
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *         {
 *           "type": "media-file",
 *           "id": "1",
 *           "attributes": {
 *             "file": "String",
 *             "file_info": "String",
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000"
 *           },
 *           "links": {
 *             "self": "/media-file/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 */

/**
 * @api {get} /media-file/:id Get media-file
 * @apiName GetMediaFile
 * @apiGroup MediaFile
 *
 * @apiDescription Get media-file.
 *
 * @apiPermission admin
 * @apiHeader {String} Authorization Bearer TOKEN
 * @apiUse UnauthorizedError
 *
 * @apiParam {Number} id Id media-file
 *
 * @apiSuccessExample {json} Success (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "media-file",
 *         "id": "1",
 *         "attributes": {
 *             "file": "String",
 *             "file_info": "String",
 *             "created_by": 1,
 *             "updated_by": 1,
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "updated_at": "2016-10-17T07:38:21+0000"
 *         },
 *         "links": {
 *           "self": "/media-file/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

/**
 * @api {delete} /media-file/:id Delete media-file
 * @apiName DeleteMediaFile
 * @apiGroup MediaFile
 *
 * @apiDescription Delete media-file.
 *
 * @apiPermission admin
 * @apiHeader {String} Authorization Bearer TOKEN
 * @apiUse UnauthorizedError
 *
 * @apiParam {Number} id Id media-file
 *
 * @apiSuccessExample {json} Success (204)
 *     HTTP/1.1 204 OK
 *
 * @apiUse StandardErrors
 * @apiUse NotFoundError
 */

final class MediaFileSchema extends BaseSchema
{
    protected $resourceType = 'media-file';

    public function getId($entity)
    {
        return $entity->id;
    }

    public function getAttributes($entity)
    {
        return [
            'file'       => (string)$entity->file,
            'file_info'  => json_decode($entity->file_info),
            'created_by' => (integer)$entity->created_by,
            'updated_by' => (integer)$entity->updated_by,
            'created_at' => Carbon::parse($entity->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'updated_at' => Carbon::parse($entity->updated_at)->setTimezone('UTC')->format(Carbon::ISO8601),
        ];
    }
}
