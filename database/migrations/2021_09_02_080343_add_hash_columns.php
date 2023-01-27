<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHashColumns extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* New hash column for public url's */
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('hash')->nullable();
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->text('hash')->nullable();
        });
        Schema::table('estimates', function (Blueprint $table) {
            $table->text('hash')->nullable();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->text('hash')->nullable();
        });
        Schema::table('proposals', function (Blueprint $table) {
            $table->text('hash')->nullable();
        });
        Schema::table('leads', function (Blueprint $table) {
            $table->text('hash')->nullable();
        });

        DB::statement('UPDATE invoices set hash=MD5(RAND())');
        DB::statement('UPDATE contracts set hash=MD5(RAND())');
        DB::statement('UPDATE estimates set hash=MD5(RAND())');
        DB::statement('UPDATE projects set hash=MD5(RAND())');
        DB::statement('UPDATE proposals set hash=MD5(RAND())');
        DB::statement('UPDATE leads set hash=MD5(RAND())');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['hash']);
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['hash']);
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn(['hash']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['hash']);
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['hash']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['hash']);
        });

    }

}
