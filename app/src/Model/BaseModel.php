<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
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

    /**
     * Model validation rules
     * @var array
     */
    public static $rules = [];

}