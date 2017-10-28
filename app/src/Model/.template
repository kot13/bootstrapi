<?= "<?php".PHP_EOL; ?>
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class <?= $className.PHP_EOL; ?>
 *
<?php foreach ($columns as $column) {
    echo " * @property ".$column['type']."\t$".$column['name'].PHP_EOL;
};
?>
 *
 * @package App\Model
 */
final class <?= $className ?> extends BaseModel
{
    use SoftDeletes;

    protected $table = '<?= $tableName ?>';

    protected $fillable = [
<?php foreach ($columns as $column) {
    if ($column['name'] === 'id') {
        continue;
    }
    echo "\t\t'".$column['name']."',".PHP_EOL;
};
?>
    ];

    public static $rules = [
<?php foreach ($columns as $column) {
    echo "\t\t'".$column['name']."' \t=> 'required',".PHP_EOL;
};
?>
    ];
}
