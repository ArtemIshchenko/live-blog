<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('birthday')->unsigned()->after('name');
            $table->tinyInteger('gender')->after('birthday_day');
            $table->string('country', 64)->after('gender');
            $table->string('city', 64)->after('country');
            $table->integer('count_unread_messages')->unsigned()->after('country');
            $table->tinyInteger('status')->after('count_unread_messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
