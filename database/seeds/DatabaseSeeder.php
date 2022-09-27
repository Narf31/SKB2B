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
        DB::raw('SET FOREIGN_KEY_CHECKS = 0;');



        DB::raw('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
