<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = env('SEED_RECORD_COUNT', 30);
        \App\Models\Expense::factory()->count((int)$count)->create();
    }

}
