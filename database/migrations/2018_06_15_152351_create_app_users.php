<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUsers extends Migration
{

    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('app_uid');
            $table->primary(['id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_users');
    }
}
