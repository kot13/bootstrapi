<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class <class>
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('<tableName>', function($table) {
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('<tableName>');
    }
}
