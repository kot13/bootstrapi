<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

    public static function getExpand(){
        return [];
    }

    public static function getRules(){
        return [];
    }

}