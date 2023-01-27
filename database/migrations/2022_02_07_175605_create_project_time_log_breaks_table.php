<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTimeLogBreaksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_time_log_breaks', function (Blueprint $table) {
            $table->id();
            
            $table->integer('project_time_log_id')->unsigned()->nullable();
            $table->foreign('project_time_log_id')->references('id')->on('project_time_logs')->onDelete('cascade')->onUpdate('cascade');

            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->text('reason');

            $table->string('total_hours')->nullable();
            $table->string('total_minutes')->nullable();

            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
            
            $table->timestamps();
        });

        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->string('total_break_minutes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_time_log_breaks');

        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->dropColumn('total_break_minutes');
        });
    }

}
