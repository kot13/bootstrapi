<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateAccessTokensTable
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('access_tokens', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('access_token')->unique()->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('access_tokens');
    }
}
