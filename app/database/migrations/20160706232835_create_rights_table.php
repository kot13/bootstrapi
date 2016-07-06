<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRightsTable{
    /**
    * Do the migration
    */
    public function up()
    {
        Capsule::schema()->create('rights', function($table)
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
        Capsule::schema()->drop('rights');
    }
}