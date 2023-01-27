<?php

use App\Models\TranslateSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslateSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translate_settings', function (Blueprint $table) {
            $table->id();
            $table->string('google_key')->nullable();
            $table->timestamps();
        });

        TranslateSetting::create([
            'google_key' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translate_settings');
    }

}
