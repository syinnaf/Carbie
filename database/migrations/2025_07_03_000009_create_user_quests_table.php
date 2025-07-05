<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQuestsTable extends Migration
{
    public function up()
    {
        Schema::create('user_quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('quest_id')->constrained('quests');
            $table->enum('status', ['active', 'completed', 'failed'])->default('active');
            $table->decimal('progress', 8, 2)->default(0);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'quest_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_quests');
    }
}


// return new class extends Migration
// {
//     public function up()
//     {
//         Schema::create('user_quests', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('user_id')->constrained()->onDelete('cascade');
//             $table->string('title');
//             $table->text('description');
//             $table->string('type'); // daily, weekly, monthly
//             $table->json('requirements'); // quest requirements
//             $table->integer('reward_xp');
//             $table->decimal('target_carbon_reduction', 8, 4)->nullable();
//             $table->boolean('is_completed')->default(false);
//             $table->datetime('started_at');
//             $table->datetime('expires_at');
//             $table->datetime('completed_at')->nullable();
//             $table->timestamps();
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('user_quests');
//     }
// };