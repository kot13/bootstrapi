<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRefreshTokensTable
{
    /**
    * Do the migration
    */
    public function up()
    {
        Capsule::schema()->create('refresh_tokens', function($table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('refresh_token')->unique()->nullable();
            $table->timestamp('created_at');
        });

    }

    /**
    * Undo the migration
    */
    public function down()
    {
        Capsule::schema()->drop('refresh_tokens');
    }
}
