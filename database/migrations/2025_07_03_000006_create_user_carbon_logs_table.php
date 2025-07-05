<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCarbonLogsTable extends Migration
{
    public function up()
    {
        Schema::create('user_carbon_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('session_id')->nullable();
            $table->foreignId('todo_id')->constrained('user_todos');
            $table->date('date');
            $table->decimal('carbon_amount', 8, 2);
            $table->string('activity_type');
            $table->json('calculation_details');
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index(['session_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_carbon_logs');
    }
}
