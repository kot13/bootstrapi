<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRolesTable
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('roles', function($table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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