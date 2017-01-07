<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Right
 *
 * @property integer        $id
 * @property string         $name
 * @property string         $description
 * @property integer        $created_by
 * @property integer        $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
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
