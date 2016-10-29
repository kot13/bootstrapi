<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * Model validation rules
     * @var array
     */
    public static $rules = [];

}