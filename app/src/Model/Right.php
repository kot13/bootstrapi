<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

final class Right extends BaseModel
{
    use SoftDeletes;

    protected $table = 'rights';

    protected $fillable = [
        'name',
        'description',
    ];

    public static $schemaName = 'App\Schema\RightSchema';

    public static $rules = [
        'name' => 'required',
    ];


}