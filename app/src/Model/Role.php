<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 *
 * @property integer        $id
 * @property string         $name
 * @property string         $description
 * @property integer        $created_by
 * @property integer        $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read Right[]   $rights
 *
 * @package App\Model
 */
final class Role extends BaseModel
{

    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function rights()
    {
        return $this->belongsToMany('App\Model\Right', 'roles_to_rights', 'role_id', 'right_id');
    }

}
