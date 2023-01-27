<?php

use App\Models\DatabaseBackupSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabaseBackupSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('database_backup_cron_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->time('hour_of_day')->nullable();
            $table->string('backup_after_days')->nullable();
            $table->string('delete_backup_after_days')->nullable();
        });

        Schema::create('database_backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('size')->nullable();
            $table->dateTime('created_at')->nullable();
        });

        $backupSetting = new DatabaseBackupSetting();
        $backupSetting->status = 'inactive';
        $backupSetting->hour_of_day = '';
        $backupSetting->backup_after_days = '0';
        $backupSetting->delete_backup_after_days = '0';
        $backupSetting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_backup_cron_settings');
        Schema::dropIfExists('database_backups');
    }

}
