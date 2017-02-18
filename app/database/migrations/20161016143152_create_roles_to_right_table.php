<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRolesToRightTable
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('roles_to_rights', function($table) {
            $table->integer('role_id')->unsigned();
            $table->integer('right_id')->unsigned();
            $table->primary(['role_id', 'right_id']);
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('roles_to_rights');
    }
}
