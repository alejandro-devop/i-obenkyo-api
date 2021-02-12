<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('text', 500);
            $table->text('description')->nullable();
            $table->boolean('is_done')->nullable();
            $table->dateTime('apply_date')->nullable();
            $table->boolean('is_all_day')->nullable();
            $table->foreignId('task_group_id')
                ->references('id')
                ->on('task_groups');
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
        Schema::dropIfExists('tasks');
    }
}
