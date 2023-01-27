<?php

namespace Database\Seeders;

use App\Models\ClientDetails;
use App\Models\EmployeeDetails;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\UniversalSearch;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        DB::table('users')->delete();
        DB::table('employee_details')->delete();
        DB::table('universal_search')->delete();

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE employee_details AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE universal_search AUTO_INCREMENT = 1');

        $count = env('SEED_RECORD_COUNT', 30);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->name = $faker->name;
        $user->email = 'admin@example.com';
        $user->password = Hash::make('123456');
        $user->save();

        $employee = new \App\Models\EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->employee_id = 'EMP-' . $user->id;
        $employee->address = $faker->address;
        $employee->hourly_rate = $faker->numberBetween(15, 100);
        $employee->joining_date = now()->subMonths(9)->toDateTimeString();
        $employee->save();

        $search = new \App\Models\UniversalSearch();
        $search->searchable_id = $user->id;
        $search->title = $user->name;
        $search->route_name = 'employees.show';
        $search->save();

        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $clientRole = Role::where('name', 'client')->first();

        $user->roles()->attach($adminRole->id); // id only
        $user->roles()->attach($employeeRole->id); // id only

        if (!App::environment('codecanyon')) {
            // Employee details

            $this->call(DepartmentTableSeeder::class);

            $user = new User();
            $user->name = $faker->name;
            $user->email = 'employee@example.com';
            $user->password = Hash::make('123456');
            $user->save();

            $search = new UniversalSearch();
            $search->searchable_id = $user->id;
            $search->title = $user->name;
            $search->route_name = 'employees.show';
            $search->save();

            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
            $employee->employee_id = 'EMP-' . $user->id;
            $employee->address = $faker->address;
            $employee->department_id = $faker->randomElement($this->getDepartment());
            $employee->designation_id = $faker->randomElement($this->getDesignation());
            $employee->hourly_rate = $faker->numberBetween(15, 100);
            $employee->joining_date = now()->subMonths(9)->toDateTimeString();
            $employee->save();

            // Assign Role
            $user->roles()->attach($employeeRole->id);

            // Client details
            $user = new User();
            $user->name = $faker->name;
            $user->email = 'client@example.com';
            $user->password = Hash::make('123456');
            $user->save();

            $search = new UniversalSearch();
            $search->searchable_id = $user->id;
            $search->title = $user->name;
            $search->route_name = 'clients.show';
            $search->save();

            $client = new ClientDetails();
            $client->user_id = $user->id;
            $client->company_name = $faker->company;
            $client->address = $faker->address;
            $client->website = $faker->url;
            $client->save();

            // Assign Role
            $user->roles()->attach($clientRole->id);

            // Multiple client create
            User::factory()->count((int)$count)->create()->each(function ($user) use ($faker, $clientRole) {
                $search = new UniversalSearch();
                $search->searchable_id = $user->id; /* @phpstan-ignore-line */
                $search->title = $user->name; /* @phpstan-ignore-line */
                $search->route_name = 'clients.show';
                $search->save();

                $client = new ClientDetails();
                $client->user_id = $user->id; /* @phpstan-ignore-line */
                $client->company_name = $faker->company;
                $client->address = $faker->address;
                $client->website = $faker->url;
                $client->save();

                // Assign Role
                $user->roles()->attach($clientRole->id); /* @phpstan-ignore-line */
            });

            // Multiple employee create
            User::factory((int)$count)->create()->each(function ($user) use ($faker, $employeeRole) {
                $search = new UniversalSearch();
                $search->searchable_id = $user->id; /* @phpstan-ignore-line */
                $search->title = $user->name; /* @phpstan-ignore-line */
                $search->route_name = 'employees.show';
                $search->save();

                $employee = new \App\Models\EmployeeDetails();
                $employee->user_id = $user->id; /* @phpstan-ignore-line */
                $employee->employee_id = 'EMP-' . $user->id; /* @phpstan-ignore-line */
                $employee->address = $faker->address;
                $employee->hourly_rate = $faker->numberBetween(15, 100);
                $employee->department_id = $faker->randomElement($this->getDepartment());
                $employee->designation_id = $faker->randomElement($this->getDesignation());
                $employee->hourly_rate = $faker->numberBetween(15, 100);
                $employee->joining_date = now()->subMonths(9)->toDateTimeString();
                $employee->save();

                // Assign Role
                $user->roles()->attach($employeeRole->id); /* @phpstan-ignore-line */
            });
        }

    }

    public function getDepartment()
    {
        return Team::inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

    public function getDesignation()
    {
        return \App\Models\Designation::inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

}
