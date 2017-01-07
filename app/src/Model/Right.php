<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Right
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
final class Right extends BaseModel
{
    use SoftDeletes;

    protected $table = 'rights';

    protected $fillable = [
        'name',
        'description',
    ];

    public static $rules = [
        'name' => 'required|unique:rights',
    ];
}
