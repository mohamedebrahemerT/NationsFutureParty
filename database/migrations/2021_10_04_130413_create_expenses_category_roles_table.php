<?php

use App\Models\ExpensesCategoryRole;
use App\Models\Role;
use App\Models\ExpensesCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesCategoryRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_category_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('expenses_category_id')->unsigned()->nullable()->default(null);
            $table->foreign('expenses_category_id')->references('id')->on('expenses_category')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });


        $expensesCategories = ExpensesCategory::all();
        $roles = Role::where('name', '<>', 'admin')->where('name', '<>', 'client')->get();

        foreach($expensesCategories as $expensesCategory){
            ExpensesCategoryRole::where('expenses_category_id', $expensesCategory->id)->delete();

            foreach($roles as $role){
                $expansesCategoryRoles = new ExpensesCategoryRole();
                $expansesCategoryRoles->expenses_category_id = $expensesCategory->id;
                $expansesCategoryRoles->role_id = $role->id;
                $expansesCategoryRoles->save();
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
        Schema::dropIfExists('expenses_category_roles');
    }

}
