<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarbonActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('carbon_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('activity_categories');
            $table->string('activity_type');
            $table->string('unit'); // km, hours, kg, etc.
            $table->decimal('carbon_factor', 8, 4); // kg CO2 per unit
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carbon_activities');
    }
}