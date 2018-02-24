<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            BranchesTableSeeder::class,
            UsersTableSeeder::class,
            ServicesTableSeeder::class,
            CountersTableSeeder::class,
            BranchServicesTableSeeder::class,
            BranchCountersTableSeeder::class,
            QueuesTableSeeder::class,
            MobileUsersTableSeeder::class,
            TicketsTableSeeder::class,
        ]);
    }
}
