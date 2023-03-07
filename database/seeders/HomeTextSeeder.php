<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HomeTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('home_texts')->insert(['type' => 'intro', 'content' => '']);
        DB::table('home_texts')->insert(['type' => 'welcome', 'content' => '']);
        DB::table('home_texts')->insert(['type' => 'educative', 'content' => '']);
    }
}
