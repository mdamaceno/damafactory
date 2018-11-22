<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDbRolesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_roles_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('db_role_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('db_role_id')->references('id')->on('db_roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('db_roles_users');
    }
}
