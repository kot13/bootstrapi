<?php
namespace App\Model;

final class RoleToRight extends BaseModel
{
    protected $table = 'roles_to_rights';

    protected $fillable = ['role_id', 'right_id'];

    protected $primaryKey = ['role_id', 'right_id'];

    public $incrementing = false;

    public $timestamps = false;

    public static function getRules()
    {
        return [
            'role_id' => 'required',
            'right_id' => 'required',
        ];
    }

}