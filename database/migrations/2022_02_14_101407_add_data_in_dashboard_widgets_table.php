<?php

use App\Models\DashboardWidget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataInDashboardWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $widget = new DashboardWidget();
        $widget->widget_name = 'birthday';
        $widget->status = 1;
        $widget->dashboard_type = 'admin-hr-dashboard';
        $widget->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DashboardWidget::where('widget_name', 'birthday')->where('dashboard_type', 'admin-hr-dashboard')->delete();
    }

}
