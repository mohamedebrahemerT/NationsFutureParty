<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RemoveHtmlTagsInProducts extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Product::get()->each(function (Product $product) {
            $product->update([
                'description' => strip_tags(Str::replace(['<br>', '<br/>', '<br />', '</p>', '</li>', '</ol>'], "\n", $product->description)),
            ]);
        });
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
