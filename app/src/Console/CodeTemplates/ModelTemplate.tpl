<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class <class>
 *
<phpdoc>
 *
 * @package App\Model
 */
final class <class> extends BaseModel
{
    use SoftDeletes;

    protected $table = '<tableName>';

    protected $fillable = [
<fillable>
    ];
}
