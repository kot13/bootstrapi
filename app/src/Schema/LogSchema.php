<?php
namespace App\Schema;

class LogSchema extends BaseSchema{

    protected $resourceType = 'log';

    public function getId($log)
    {
        return $log->id;
    }

    public function getAttributes($log)
    {
        return [
            'action' => $log->action,
            'entity_id' => $log->entity_id,
            'entity_type' => $log->entity_type,
            'state' => $log->state,
            'created_at' => $log->created_at,
            'created_by' => $log->created_by,
        ];
    }
}