<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateUsersTable{
    /**
    * Do the migration
    */
    public function up()
    {
        Capsule::schema()->create('users', function($table)
        {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('full_name');
            $table->string('password');
            $table->string('access_token')->unique()->nullable();
            $table->string('role_id');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('status');
        });

    }

    /**
    * Undo the migration
    */
    public function down()
    {
        Capsule::schema()->drop('users');
    }
}