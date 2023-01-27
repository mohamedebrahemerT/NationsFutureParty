<?php

use App\Models\DashboardWidget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDashboardPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        DashboardWidget::firstOrCreate([
            'dashboard_type' => 'admin-dashboard',
            'widget_name' => 'timelogs',
            'status' => 1
        ]);

        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'proposal_tab')->delete();
        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'due_payments_tab')->delete();
        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'payment_tab')->delete();
        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'invoice_tab')->delete();
        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'estimate_tab')->delete();
        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'expense_tab')->delete();
        DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('widget_name', 'total_profit')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DashboardWidget::where('dashboard_type', 'admin-dashboard')->where('widget_name', 'timelogs')->delete();
    }

}
