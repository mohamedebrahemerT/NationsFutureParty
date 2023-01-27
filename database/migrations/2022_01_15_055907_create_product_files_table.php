<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\Product;
use App\Models\ProductFiles;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {

        if (!Schema::hasTable('product_files')) {

            Schema::create('product_files', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->unsigned();
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->string('filename', 200)->nullable();
                $table->string('hashname', 200)->nullable();
                $table->string('size', 200)->nullable();
                $table->integer('added_by')->unsigned()->nullable();
                $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

                $table->integer('last_updated_by')->unsigned()->nullable();
                $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
            });

            // Add file permission
            $admins = RoleUser::where('role_id', '1')->get();
            $allTypePermisison = PermissionType::where('name', 'all')->first();

            $productCustomPermisisons = [
                'view_product_files',
                'add_product_files',
                'edit_product_files',
                'delete_product_files',
            ];

            foreach ($productCustomPermisisons as $permission) {
                $perm = Permission::create([
                    'name' => $permission,
                    'display_name' => ucwords(str_replace('_', ' ', $permission)),
                    'is_custom' => 1,
                    'module_id' => 16
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

        $products = Product::whereNotNull('image')->get();

        foreach ($products as $product) {

            // Insert image to product_files table
            $file = new ProductFiles();
            $file->product_id = $product->id;
            $file->hashname = $product->image;
            $file->filename = $product->image;
            $file->added_by = $product->added_by;
            $file->size = Storage::disk(config('filesystems.default'))->size(ProductFiles::FILE_PATH.'/' . $product->image);
            $file->save();
        }

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('image', 'default_image');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_files');

        Permission::where('name', 'view_product_files')->delete();
        Permission::where('name', 'add_product_files')->delete();
        Permission::where('name', 'edit_product_files')->delete();
        Permission::where('name', 'delete_product_files')->delete();
    }

}
