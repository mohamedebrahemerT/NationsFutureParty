<?php

namespace Database\Seeders;

use App\Models\CompanyAddress;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class CompanyAddressSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Setting::first();

        if (!is_null($setting)) {
            CompanyAddress::create([
                'address' => $setting->address,
                'location' => $setting->company_name,
                'is_default' => 1
            ]);
        }
    }

}
