<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGreenRecommendationsTable extends Migration
{
    public function up()
    {
        Schema::create('green_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('category_id')->constrained('activity_categories');
            $table->decimal('carbon_threshold', 8, 2); // show when daily carbon exceeds this
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('green_recommendations');
    }
}
