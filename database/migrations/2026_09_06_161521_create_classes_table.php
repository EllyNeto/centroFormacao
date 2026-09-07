<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('room')->nullable();
            $table->string('days_of_week');
            $table->string('shift')->default('Manhã');
            $table->integer('capacity')->default(25);
            $table->boolean('status')->default(true);
            $table->time('start_time'); // Coluna para hora de início
            $table->time('end_time');   // Coluna para hora de fim
            $table->foreignId('teacher_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
