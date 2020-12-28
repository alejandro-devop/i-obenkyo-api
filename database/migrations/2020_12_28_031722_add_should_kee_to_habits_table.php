<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShouldKeeToHabitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->boolean('should_avoid')->default(false)->nullable()->after('user_id');
            $table->boolean('should_keep')->default(false)->nullable()->after('user_id');
            $table->integer('max_streak')->default(0)->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn('should_keep');
            $table->dropColumn('should_avoid');
            $table->dropColumn('max_streak');
        });
    }
}
