<?php

use App\Models\Order;
use App\Models\Permission;
use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderStatusToOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $paidOrders = Order::where('status', 'paid')->pluck('id')->toArray();

        $unpaidOrders = Order::where('status', 'unpaid')->pluck('id')->toArray();

        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `status` ENUM("pending", "on-hold", "failed", "processing", "completed", "canceled", "refunded") NOT NULL DEFAULT "pending"');

        Order::whereIn('id', $paidOrders)->update(['status' => 'completed']);
        Order::whereIn('id', $unpaidOrders)->update(['status' => 'pending']);

        Permission::where('name', 'add_order')->update(['allowed_permissions' => '{"all":4, "none":5}']);
        Permission::whereIn('name', ['view_order', 'edit_order', 'delete_order'])->update(['allowed_permissions' => '{"all":4, "added":1, "owned":2,"both":3, "none":5}']);

        ModuleSetting::where('type', 'client')->where('module_name', 'products')->delete();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }

}
