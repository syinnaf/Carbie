<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
    /**
     * Run the migrations.
     */
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nickname');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->decimal('daily_carbon_limit', 8, 2)->default(12.50);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}