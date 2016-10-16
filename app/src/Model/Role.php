<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

final class Role extends BaseModel
{

    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];

    public static $schemaName = 'App\Schema\RoleSchema';

    public static $expand = [
        'rights' => 'App\Model\Right',
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function rights()
    {
        return $this->belongsToMany('App\Model\Right', 'roles_to_rights', 'role_id', 'right_id');
    }

}