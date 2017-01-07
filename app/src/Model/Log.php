<?php
namespace App\Model;

/**
 * Class Log
 *
 *
 *
 * @package App\Model
 */
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

}
