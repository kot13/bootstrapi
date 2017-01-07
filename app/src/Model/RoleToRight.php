<?php
namespace App\Model;

/**
 * Class RoleToRight
 *
 * @property integer $role_id
 * @property integer $right_id
 *
 * @package App\Model
 */
final class RoleToRight extends BaseModel
{
    protected $table = 'roles_to_rights';

    protected $fillable = ['role_id', 'right_id'];

    protected $primaryKey = ['role_id', 'right_id'];

    public $incrementing = false;

    public $timestamps = false;

    public static $rules = [
        'role_id' => 'required',
        'right_id' => 'required',
    ];

}
