<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrequencyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frequency_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days')->nullable();
            $table->boolean('is_daily')->nullable();
            $table->boolean('is_weekly')->nullable();
            $table->boolean('is_monthly')->nullable();
            $table->boolean('is_every_year')->nullable();
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
        Schema::dropIfExists('frequency_types');
    }
}
