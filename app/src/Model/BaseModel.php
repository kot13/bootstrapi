<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\MaxPerPageScope;

/**
 * Class BaseModel
 *
 * @property integer $id
 *
 * @method static find($id)
 * @method static where($filed, $value)
 * @method static create($attributes)
 *
 * @package App\Model
 */
abstract class BaseModel extends Model
{
    /**
     * Global scope
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new MaxPerPageScope);
    }

    /**
     * Default scope - ALL
     * @param $query
     *
     * @return mixed
     */
    public function scopeCurrentUser($query)
    {
        return $query;
    }
}
