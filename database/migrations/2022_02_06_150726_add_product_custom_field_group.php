<?php

use App\Models\CustomFieldGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductCustomFieldGroup extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $group = new CustomFieldGroup();
        $group->name = 'Product';
        $group->model = 'App\Models\Product';
        $group->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CustomFieldGroup::where('name', 'Product')->delete();
    }

}
