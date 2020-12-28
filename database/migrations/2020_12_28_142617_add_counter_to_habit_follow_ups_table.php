<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCounterToHabitFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('habit_follow_ups', function (Blueprint $table) {
            $table->integer('counter')->default(0)->nullable();
            $table->integer('counter_goal')->default(0)->nullable();
            $table->boolean('is_counter')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('habit_follow_ups', function (Blueprint $table) {
            $table->dropColumn('counter');
            $table->dropColumn('counter_goal');
            $table->dropColumn('is_counter');
        });
    }
}
