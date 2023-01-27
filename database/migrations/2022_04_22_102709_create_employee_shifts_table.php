<?php

use App\Models\AttendanceSetting;
use App\Models\EmployeeShift;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeShiftsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');
            $table->string('shift_short_code');
            $table->string('color');
            $table->time('office_start_time');
            $table->time('office_end_time');
            $table->time('halfday_mark_time')->nullable();
            $table->tinyInteger('late_mark_duration');
            $table->tinyInteger('clockin_in_day');
            $table->text('office_open_days');
            $table->timestamps();
        });

        Schema::create('employee_shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->bigInteger('employee_shift_id')->unsigned();
            $table->foreign('employee_shift_id')->references('id')->on('employee_shifts')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
            $table->timestamps();
        });

        $attendanceSettings = AttendanceSetting::first();

        $employeeShift = new EmployeeShift();
        $employeeShift->shift_name = 'General Shift';
        $employeeShift->shift_short_code = 'GS';
        $employeeShift->color = '#99C7F1';
        $employeeShift->office_start_time = $attendanceSettings->office_start_time;
        $employeeShift->office_end_time = $attendanceSettings->office_end_time;
        $employeeShift->halfday_mark_time = $attendanceSettings->halfday_mark_time;
        $employeeShift->late_mark_duration = $attendanceSettings->late_mark_duration;
        $employeeShift->clockin_in_day = $attendanceSettings->clockin_in_day;
        $employeeShift->office_open_days = $attendanceSettings->office_open_days;
        $employeeShift->save();

        Schema::table('attendance_settings', function (Blueprint $table) use ($employeeShift) {
            $table->bigInteger('default_employee_shift')->unsigned()->nullable()->default($employeeShift->id);
            $table->foreign('default_employee_shift')->references('id')->on('employee_shifts')->onDelete('SET  NULL')->onUpdate('cascade');
            $table->string('week_start_from')->default(1);
        });

        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();
        $module = Module::where('module_name', 'attendance')->first();

        $employeeCustomPermisisons = [
            'manage_employee_shifts'
        ];

        foreach ($employeeCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => $module->id,
                'allowed_permissions' => '{"all":4, "none":5}'
            ]);

            foreach ($admins as $item) {
                UserPermission::create(
                    [
                        'user_id' => $item->user_id,
                        'permission_id' => $perm->id,
                        'permission_type_id' => $allTypePermisison->id
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropForeign(['default_employee_shift']);
            $table->dropColumn(['default_employee_shift']);
            $table->dropColumn(['week_start_from']);
        });

        Permission::where('name', 'manage_employee_shifts')->delete();
        Schema::dropIfExists('employee_shift_schedules');
        Schema::dropIfExists('employee_shifts');
    }

}
