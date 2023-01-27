<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecurringColumnsTasks extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('repeat')->default(0);
            $table->boolean('repeat_complete')->default(0);
            $table->integer('repeat_count')->nullable();
            $table->enum('repeat_type', ['day', 'week', 'month', 'year'])->default('day');
            $table->integer('repeat_cycles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['repeat', 'repeat_count', 'repeat_type', 'repeat_cycles', 'repeat_complete']);
        });
    }

}
