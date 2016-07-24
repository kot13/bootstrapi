<?php
namespace App\Model;

final class Log extends BaseModel
{

    protected $table = 'logs';

    protected $fillable = [
        'action',
        'entity_id',
        'entity_type',
        'state',
        'created_by',
    ];

    public $timestamps = false;

    public static $schemaName = 'App\Schema\LogSchema';

}