<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDbsDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dbs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->unique();
            $table->string('driver');
            $table->string('host');
            $table->string('port');
            $table->string('database');
            $table->string('username');
            $table->string('password')->nullable()->default('');
            $table->string('charset')->default('utf8');
            $table->string('prefix')->nullable()->default('');
            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dbs');
    }
}
