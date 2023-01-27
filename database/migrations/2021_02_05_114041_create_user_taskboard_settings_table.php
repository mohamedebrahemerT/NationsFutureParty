<?php

use App\Models\TaskboardColumn;
use App\Models\User;
use App\Models\UserTaskboardSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTaskboardSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_taskboard_settings', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('board_column_id')->unsigned();
            $table->foreign('board_column_id')->references('id')->on('taskboard_columns')->onDelete('restrict')->onUpdate('cascade');

            $table->boolean('collapsed')->default(0);

            $table->timestamps();
        });

        $employees = User::allEmployees();
        $taskBoardColumn = TaskboardColumn::all();

        if (!is_null($employees) && !is_null($taskBoardColumn)) {
            foreach ($employees as $item) {
                foreach ($taskBoardColumn as $board) {
                    UserTaskboardSetting::create([
                        'user_id' => $item->id,
                        'board_column_id' => $board->id
                    ]);
                }
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
        Schema::dropIfExists('user_taskboard_settings');
    }

}
