<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRolesTable
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('roles', function($table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('roles');
    }
}
