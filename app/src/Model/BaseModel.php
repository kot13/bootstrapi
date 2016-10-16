<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public static $expand = [];

    public static $rules = [];

}