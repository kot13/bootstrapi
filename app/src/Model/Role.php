<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 *
 * @property integer $id
 * @property string  $name
 * @property string  $description
 * @property integer $created_by
 * @property integer $updated_by
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $deleted_at
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
