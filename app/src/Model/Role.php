<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Common\CreatedByUpdatedByTrait;

final class Role extends BaseModel
{

    use SoftDeletes;
    use CreatedByUpdatedByTrait;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];

    public static function getSchemaName()
    {
        return 'App\Schema\RoleSchema';
    }

    public static function getExpand()
    {
        return [
            'rights' => 'App\Model\Right',
        ];
    }

    public static function getRules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function rights()
    {
        return $this->belongsToMany('App\Model\Right', 'roles_to_rights', 'role_id', 'right_id');
    }

}