<?php
namespace App\Model;

/**
 * Class Log
 *
 * @property integer        $id
 * @property integer        $entity_id
 * @property string         $entity_type
 * @property string         $state
 * @property \Carbon\Carbon $created_at
 * @property integer        $created_by
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
