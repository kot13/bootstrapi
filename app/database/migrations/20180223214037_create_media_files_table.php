<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateMediaFilesTable
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('media_files', function($table) {
            $table->increments('id');
            $table->string('file');
            $table->string('file_info');
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('media_files');
    }
}
