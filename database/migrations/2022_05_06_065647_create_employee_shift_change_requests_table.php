<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeShiftChangeRequestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_shift_change_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shift_schedule_id')->unsigned();
            $table->foreign('shift_schedule_id')->references('id')->on('employee_shift_schedules')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('employee_shift_id')->unsigned();
            $table->foreign('employee_shift_id')->references('id')->on('employee_shifts')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['waiting', 'accepted', 'rejected'])->default('waiting');
            $table->timestamps();
        });

        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->boolean('allow_shift_change')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_shift_change_requests');

        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropColumn(['allow_shift_change']);
        });
    }

}
