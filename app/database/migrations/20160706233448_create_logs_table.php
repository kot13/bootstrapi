<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateLogsTable
{
    /**
    * Do the migration
    */
    public function up()
    {
        Capsule::schema()->create('logs', function($table)
        {
            $table->increments('id');
            $table->string('action');
            $table->morphs('entity');
            $table->text('state')->nullable();
            $table->timestamp('created_at');
            $table->integer('created_by');
        });

    }

    /**
    * Undo the migration
    */
    public function down()
    {
        Capsule::schema()->drop('logs');
    }
}
