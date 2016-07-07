<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Common\CreatedByUpdatedByTrait;
use App\Common\LoggingTrait;

final class Right extends BaseModel
{

    use SoftDeletes;
    use CreatedByUpdatedByTrait;
    use LoggingTrait;

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