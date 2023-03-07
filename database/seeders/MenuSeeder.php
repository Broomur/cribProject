<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert(['weekday' => 'monday', 'PM_menu' => '', 'BB_menu' => '', 'snack' => '', 'user_id' => '1']);
        DB::table('menus')->insert(['weekday' => 'tuesday', 'PM_menu' => '', 'BB_menu' => '', 'snack' => '', 'user_id' => '1']);
        DB::table('menus')->insert(['weekday' => 'wednesday', 'PM_menu' => '', 'BB_menu' => '', 'snack' => '', 'user_id' => '1']);
        DB::table('menus')->insert(['weekday' => 'thursday', 'PM_menu' => '', 'BB_menu' => '', 'snack' => '', 'user_id' => '1']);
        DB::table('menus')->insert(['weekday' => 'friday', 'PM_menu' => '', 'BB_menu' => '', 'snack' => '', 'user_id' => '1']);
    }
}
