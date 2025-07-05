<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTodosTable extends Migration
{
    public function up()
    {
        Schema::create('user_todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('session_id')->nullable(); // for guest mode
            $table->string('title');
            $table->foreignId('category_id')->constrained('activity_categories');
            $table->json('details'); // vehicle, distance, device, duration, weight
            $table->date('date');
            $table->enum('status', ['pending', 'done'])->default('pending');
            $table->decimal('carbon_value', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index(['session_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_todos');
    }
}
