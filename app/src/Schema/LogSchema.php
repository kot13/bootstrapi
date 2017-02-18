<?php
namespace App\Schema;

use \Carbon\Carbon;

/**
 * @api {get} /log?page[number]=1&page[size]=15 Список логов
 * @apiName GetLogs
 * @apiGroup Log
 *
 * @apiDescription Метод для получения списка логов.
 *
 * @apiPermission user
 *
 * @apiHeader {String} Authorization Токен.
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "meta": {
 *         "total": 21,
 *         "count": 5
 *       },
 *       "links": {
 *         "self": "http://bootstrapi.dev/api/log?page[number]=1&page[size]=5",
 *         "first": "http://bootstrapi.dev/api/log?page[number]=1&page[size]=5",
 *         "last": "http://bootstrapi.dev/api/log?page[number]=5&page[size]=5",
 *         "next": "http://bootstrapi.dev/api/log?page[number]=2&page[size]=5"
 *       },
 *       "data": [
 *         {
 *           "type": "log",
 *           "id": "1",
 *           "attributes": {
 *             "action": "CREATE",
 *             "entity_id": 1,
 *             "entity_type": "App\\Model\\Right",
 *             "state": "{\"name\":\"manageUsers\",\"description\":\"\\u0423\\u043f\\u0440\\u0430\\u0432\\u043b\\u0435\\u043d\\u0438\\u0435 \\u043f\\u043e\\u043b\\u044c\\u0437\\u043e\\u0432\\u0430\\u0442\\u0435\\u043b\\u044f\\u043c\\u0438\",\"created_by\":1,\"updated_at\":\"2016-10-16 18:19:34\",\"created_at\":\"2016-10-16 18:19:34\",\"id\":1}",
 *             "created_at": "2016-10-17T07:38:21+0000",
 *             "created_by": 1
 *           },
 *           "links": {
 *             "self": "http://bootstrapi.dev/api/log/1"
 *           }
 *         }
 *       ]
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 */

/**
 * @api {get} /log/:id Получить лог
 * @apiName GetLog
 * @apiGroup Log
 *
 * @apiDescription Метод для получения лога.
 *
 * @apiPermission user
 *
 * @apiParam {Number} id Id лога
 *
 * @apiHeader {String} Authorization Токен.
 *
 * @apiSuccessExample {json} Успешно (200)
 *     HTTP/1.1 200 OK
 *     {
 *       "data": {
 *         "type": "log",
 *         "id": "1",
 *         "attributes": {
 *           "action": "CREATE",
 *           "entity_id": 1,
 *           "entity_type": "App\\Model\\Right",
 *           "state": "{\"name\":\"manageUsers\",\"description\":\"\\u0423\\u043f\\u0440\\u0430\\u0432\\u043b\\u0435\\u043d\\u0438\\u0435 \\u043f\\u043e\\u043b\\u044c\\u0437\\u043e\\u0432\\u0430\\u0442\\u0435\\u043b\\u044f\\u043c\\u0438\",\"created_by\":1,\"updated_at\":\"2016-10-16 18:19:34\",\"created_at\":\"2016-10-16 18:19:34\",\"id\":1}",
 *           "created_at": "2016-10-17T07:38:21+0000",
 *           "created_by": 1
 *         },
 *         "links": {
 *           "self": "http://bootstrapi.dev/api/log/1"
 *         }
 *       }
 *     }
 *
 * @apiUse StandardErrors
 * @apiUse UnauthorizedError
 * @apiUse NotFoundError
 */

final class LogSchema extends BaseSchema
{
    protected $resourceType = 'log';

    public function getId($log)
    {
        return $log->id;
    }

    public function getAttributes($log)
    {
        return [
            'action'      => $log->action,
            'entity_id'   => (int)$log->entity_id,
            'entity_type' => $log->entity_type,
            'state'       => $log->state,
            'created_at'  => Carbon::parse($log->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'created_by'  => $log->created_by,
        ];
    }
}
