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
        Service::create(['code' => 'S1', 'name' => 'Customer Service']);
        Service::create(['code' => 'S2', 'name' => 'Deposit']);
        Service::create(['code' => 'S3', 'name' => 'Other Services']);
    }
}
