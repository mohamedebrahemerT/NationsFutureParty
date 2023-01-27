<?php

use Illuminate\Database\Migrations\Migration;

class ChangeDashboardWidgetName extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\DashboardWidget::where('widget_name', 'total_unresolved_tickets')->where('dashboard_type', 'admin-ticket-dashboard')->update(['widget_name' => 'total_tickets']);

        \App\Models\DashboardWidget::create(
            [
                'widget_name' => 'total_unpaid_invoices',
                'status' => 1,
                'dashboard_type' => 'admin-finance-dashboard'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
