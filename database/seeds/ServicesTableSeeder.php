<?php

use Illuminate\Database\Seeder;
use App\Service; 

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create(['id' => 'S1', 'name' => 'Customer Service']);
        Service::create(['id' => 'S2', 'name' => 'Deposit']);
        Service::create(['id' => 'S3', 'name' => 'Other Services']);
    }
}
