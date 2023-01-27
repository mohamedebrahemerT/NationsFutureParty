<?php

use App\Models\PermissionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade')->onUpdate('cascade');
            
            $table->bigInteger('permission_type_id')->unsigned();
            $table->foreign('permission_type_id')->references('id')->on('permission_types')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
       
        $nonePermisison = PermissionType::where('name', 'none')->first();

        Schema::table('permission_role', function (Blueprint $table) use ($nonePermisison) {
            $table->bigInteger('permission_type_id')->unsigned()->default($nonePermisison->id);
            $table->foreign('permission_type_id')->references('id')->on('permission_types')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign(['permission_type_id']);
            $table->dropColumn(['permission_type_id']);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['is_custom']);
        });
        Schema::dropIfExists('user_permissions');
    }

}
