<?php

use App\Models\EmployeeLeaveQuota;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsValueTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'value')){
                $table->double('value')->nullable()->default(0);
                $table->unsignedInteger('currency_id')->nullable()->default(null);
                $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null')->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('value');
            $table->dropColumn('currency_id');
        });
    }

}
